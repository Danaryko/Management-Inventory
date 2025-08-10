<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','min:6','confirmed'], // kirim password_confirmation
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => $data['password'],
            'roles' => 'admin', // role publik default 'operator'
        ]);

        // $token = $user->createToken('auth')->plainTextToken;

        // return response()->json(['user'=>$user,'token'=>$token], 201);
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard')) // pastikan route name 'dashboard' ada
                ->with('success', 'Login berhasil. Selamat datang!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function profile(Request $request)
    {
        return view('profile.show', ['user' => $request->user()]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // public function logoutAll(Request $request)
    // {
        // Logout sesi lain (butuh password user saat ini):
        // Auth::logoutOtherDevices($request->input('current_password'));

        // Jika pakai session driver 'database', bisa hapus semua sesi user:
        // \DB::table('sessions')->where('user_id', $request->user()->id)->delete();

    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect()->route('login')->with('success', 'Berhasil logout dari semua perangkat.');
    // }
}
