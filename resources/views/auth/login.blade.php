@extends('layouts.app')

@section('title', 'Login')
@section('page_title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Login</h2>
    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
      </div>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <input id="remember" type="checkbox" name="remember" class="rounded border-gray-300">
          <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
        </div>
        <a class="text-sm text-gray-600 hover:underline" href="#">Lupa password?</a>
      </div>
      <button class="w-full bg-gray-900 text-white font-medium py-2.5 rounded-lg hover:opacity-90">Masuk</button>
    </form>

    <p class="text-sm text-gray-600 mt-4">
      Belum punya akun?
      <a class="text-gray-900 font-medium hover:underline" href="{{ route('register') }}">Register</a>
    </p>
  </div>
</div>
@endsection
