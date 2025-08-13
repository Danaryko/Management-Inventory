<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','email','max:255','unique:users,email'],
                'password' => ['required','min:6','confirmed'],
            ]);

            $user = User::create($data); // cast "password" => hashed
            $token = $user->createToken('auth')->plainTextToken;

            return ResponseFormatter::success(
                ['user' => $user, 'token' => $token],
                'Registered'
            );
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Registration failed', 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $cred = $request->validate([
                'email' => ['required','email'],
                'password' => ['required'],
            ]);

            $user = User::where('email', $cred['email'])->first();
            if (!$user || !Hash::check($cred['password'], $user->password)) {
                return ResponseFormatter::error(null, 'Invalid credentials', 422);
            }

            $token = $user->createToken('auth')->plainTextToken;

            return ResponseFormatter::success(
                ['user' => $user, 'token' => $token],
                'Logged in'
            );
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Login failed', 500);
        }
    }

    public function profile(Request $request) // atau "profile" jika mau konsisten
    {
        return ResponseFormatter::success($request->user(), 'OK');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success(null, 'Logged out');
    }

    // public function logoutAll(Request $request)
    // {
    //     $request->user()->tokens()->delete();
    //     return ResponseFormatter::success(null, 'Logged out from all devices');
    // }
}
