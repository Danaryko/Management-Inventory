@extends('layouts.app')

@section('title', 'Register')
@section('page_title', 'Daftar')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Buat Akun</h2>
    <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password" required
                 class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" required
                 class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
        </div>
      </div>

      {{-- Optional: pilih role saat register (atau biarkan default "user") --}}
      {{-- <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select name="role" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900">
          <option value="user" {{ old('role')==='user'?'selected':'' }}>User</option>
          <option value="staff" {{ old('role')==='staff'?'selected':'' }}>Staff</option>
        </select>
      </div> --}}

      <button class="w-full bg-gray-900 text-white font-medium py-2.5 rounded-lg hover:opacity-90">Daftar</button>
    </form>

    <p class="text-sm text-gray-600 mt-4">
      Sudah punya akun?
      <a class="text-gray-900 font-medium hover:underline" href="{{ route('login') }}">Login</a>
    </p>
  </div>
</div>
@endsection
