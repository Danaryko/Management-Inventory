<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     * Query: search, per_page (default 10)
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);

        $q = Category::query()
            ->when($request->filled('search'), function ($qb) use ($request) {
                $s = $request->search;
                $qb->where(function ($x) use ($s) {
                    $x->where('name', 'like', "%{$s}%")
                      ->orWhere('description', 'like', "%{$s}%");
                });
            })
            ->withCount('products')
            ->latest();

        $p = $q->paginate($perPage);

        return ResponseFormatter::success([
            'items' => $p->items(),
            'pagination' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
            ],
        ], 'OK');
    }

    /**
     * POST /api/categories
     * Body: name, description?
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'        => ['required','string','max:255','unique:categories,name'],
                'description' => ['nullable','string'],
            ]);

            $category = Category::create($data);

            return ResponseFormatter::success($category, 'Created');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Create failed', 500);
        }
    }

    /**
     * GET /api/categories/{category}
     * Query: products_limit (default 10)
     */
    public function show(Request $request, Category $category)
    {
        $limit = (int) $request->integer('products_limit', 10);

        $category->loadCount('products');
        $category->load(['products' => function ($q) use ($limit) {
            $q->latest()->take($limit);
        }]);

        return ResponseFormatter::success($category, 'OK');
    }

    /**
     * PUT /api/categories/{category}
     * Body: name, description?
     */
    public function update(Request $request, Category $category)
    {
        try {
            $data = $request->validate([
                'name'        => ['required','string','max:255', Rule::unique('categories','name')->ignore($category->id)],
                'description' => ['nullable','string'],
            ]);

            $category->update($data);

            return ResponseFormatter::success($category, 'Updated');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Update failed', 500);
        }
    }

    /**
     * DELETE /api/categories/{category}
     * Tidak menghapus jika masih punya products.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return ResponseFormatter::error(
                null,
                'Cannot delete category with existing products.',
                422
            );
        }

        $category->delete();

        return ResponseFormatter::success(null, 'Deleted');
    }
}
