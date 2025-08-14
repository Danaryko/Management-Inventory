<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()
            ->when($request->search, fn($qb) =>
                $qb->where(fn($x) =>
                    $x->where('name','like',"%{$request->search}%")
                      ->orWhere('email','like',"%{$request->search}%")
                )
            )
            ->orderBy('id','desc');

        $p = $q->paginate($request->integer('per_page', 15));

        // letakkan info pagination di dalam "data"
        return ResponseFormatter::success([
            'items' => $p->items(),
            'pagination' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
            ]
        ], 'OK');
    }

    public function show(User $user)
    {
        return ResponseFormatter::success($user, 'OK');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'     => ['required','string','max:255'],
                'email'    => ['required','email','max:255','unique:users,email'],
                'password' => ['required','min:6'],
                'role'     => ['required', Rule::in(['admin','staff','user'])],
            ]);
            $user = User::create($data);

            return ResponseFormatter::success($user, 'Created');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Create failed', 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $data = $request->validate([
                'name'     => ['sometimes','string','max:255'],
                'email'    => ['sometimes','email','max:255', Rule::unique('users','email')->ignore($user->id)],
                'password' => ['sometimes','nullable','min:6'],
                'role'     => ['sometimes', Rule::in(['admin','staff','user'])],
            ]);

            if (array_key_exists('password', $data) && $data['password'] === null) {
                unset($data['password']);
            }

            $user->update($data);
            return ResponseFormatter::success($user, 'Updated');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Update failed', 500);
        }
    }

    public function updateRole(Request $request, User $user)
    {
        try {
            $data = $request->validate([
                'role' => ['required', Rule::in(['admin','staff','user'])],
            ]);
            $user->update(['role' => $data['role']]);

            return ResponseFormatter::success($user, 'Role updated');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return ResponseFormatter::success(null, 'Deleted');
    }
}
