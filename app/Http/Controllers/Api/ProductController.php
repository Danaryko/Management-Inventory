<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * GET /api/products
     * Query:
     *  - search: string
     *  - category_id: int
     *  - low_stock: 1|true
     *  - per_page: int (default 10)
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);

        $q = Product::with('category')
            ->when($request->filled('search'), function ($qb) use ($request) {
                $s = $request->search;
                $qb->where(function ($x) use ($s) {
                    $x->where('name', 'like', "%{$s}%")
                      ->orWhere('description', 'like', "%{$s}%");
                });
            })
            ->when($request->filled('category_id'), fn($qb) =>
                $qb->where('category_id', $request->category_id)
            )
            ->when($request->boolean('low_stock'), fn($qb) =>
                $qb->whereColumn('stock_quantity', '<=', 'min_stock_level')
            )
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
     * POST /api/products
     * Form-Data / JSON (file upload gunakan multipart/form-data)
     * Body:
     *  name (required), category_id (required), description?, brand?, size?, color?,
     *  stock_quantity (required, int >=0), min_stock_level (required, int >=0), image?
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'            => ['required','string','max:255'],
                'category_id'     => ['required','exists:categories,id'],
                'description'     => ['nullable','string'],
                'brand'           => ['nullable','string','max:255'],
                'size'            => ['nullable','string','max:255'],
                'color'           => ['nullable','string','max:255'],
                'stock_quantity'  => ['required','integer','min:0'],
                'min_stock_level' => ['required','integer','min:0'],
                'image'           => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data)->load('category');

            return ResponseFormatter::success($product, 'Created');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Create failed', 500);
        }
    }

    /**
     * GET /api/products/{product}
     * Query: (opsional) include_relations=1 untuk include relasi tambahan
     */
    public function show(Request $request, Product $product)
    {
        $product->load('category');

        // kalau perlu include relasi lain, contoh:
        // if ($request->boolean('include_relations')) {
        //     $product->load(['stockInItems' => fn($q) => $q->latest()->take(5),
        //                     'stockOutItems' => fn($q) => $q->latest()->take(5)]);
        // }

        return ResponseFormatter::success($product, 'OK');
    }

    /**
     * PUT /api/products/{product}
     */
    public function update(Request $request, Product $product)
    {
        try {
            $data = $request->validate([
                'name'            => ['required','string','max:255'],
                'category_id'     => ['required','exists:categories,id'],
                'description'     => ['nullable','string'],
                'brand'           => ['nullable','string','max:255'],
                'size'            => ['nullable','string','max:255'],
                'color'           => ['nullable','string','max:255'],
                'stock_quantity'  => ['required','integer','min:0'],
                'min_stock_level' => ['required','integer','min:0'],
                'image'           => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
            ]);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            return ResponseFormatter::success($product->load('category'), 'Updated');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Update failed', 500);
        }
    }

    /**
     * DELETE /api/products/{product}
     * Tidak boleh hapus jika punya pergerakan stok.
     */
    public function destroy(Product $product)
    {
        // Cek relasi pergerakan stok (sesuaikan nama relasi model kamu)
        if (method_exists($product, 'stockInItems') && $product->stockInItems()->exists()) {
            return ResponseFormatter::error(null, 'Cannot delete product with existing stock-in items.', 422);
        }
        if (method_exists($product, 'stockOutItems') && $product->stockOutItems()->exists()) {
            return ResponseFormatter::error(null, 'Cannot delete product with existing stock-out items.', 422);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return ResponseFormatter::success(null, 'Deleted');
    }

    /**
     * (Opsional) GET /api/products/filters
     * Untuk dropdown filter di FE (daftar kategori + info low_stock count)
     */
    public function filters()
    {
        $categories = Category::select('id','name')->orderBy('name')->get();
        $lowStockCount = Product::whereColumn('stock_quantity','<=','min_stock_level')->count();

        return ResponseFormatter::success([
            'categories'     => $categories,
            'low_stock_count'=> $lowStockCount,
        ], 'OK');
    }
}
