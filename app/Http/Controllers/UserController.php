<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // LIST + SEARCH
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn ($qb) =>
                $qb->where(function ($x) use ($request) {
                    $x->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
                })
            )
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString(); // biar query ?search tetap nempel di pagination

        return view('users.index', compact('users'));
    }

    // DETAIL
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','min:6'],
            'roles'     => ['required', Rule::in(['admin','owner','staff'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('status', 'User berhasil dibuat.');
    }

    // UPDATE
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['sometimes','string','max:255'],
            'email'    => ['sometimes','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['sometimes','nullable','min:6'],
            'roles'     => ['sometimes', Rule::in(['admin','owner','staff'])],
        ]);

        if (array_key_exists('password', $data)) {
            if ($data['password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // kosong → jangan ubah
            }
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('status', 'User berhasil diperbarui.');
    }

    // UPDATE ROLE SAJA
    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => ['required', Rule::in(['admin','owner','staff'])],
        ]);

        $user->update(['roles' => $data['roles']]);

        return back()->with('status', 'Role user berhasil diubah.');
    }

    // DELETE (soft delete jika model pakai SoftDeletes)
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('status', 'User berhasil dihapus.');
    }
}
