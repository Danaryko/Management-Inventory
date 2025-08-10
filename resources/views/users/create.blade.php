@extends('layouts.app')

@section('title', 'Tambah User')
@section('page_title', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl">

  {{-- Header with Back Button --}}
  <div class="flex items-center space-x-3 mb-6">
    <a href="{{ route('users.index') }}" 
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      Kembali
    </a>
    <h1 class="text-2xl font-semibold text-gray-900">Tambah User Baru</h1>
  </div>

  {{-- Create User Form --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6 sm:p-8">
    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-900">Informasi User</h2>
      <p class="text-sm text-gray-600 mt-1">Lengkapi form di bawah untuk menambahkan user baru</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
            <div class="mt-2 text-sm text-red-700">
              <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
      @csrf
      
      {{-- Name Field --}}
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
          Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input 
          id="name"
          type="text" 
          name="name" 
          value="{{ old('name') }}" 
          required
          autofocus
          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
          placeholder="Masukkan nama lengkap"
        >
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email Field --}}
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
          Alamat Email <span class="text-red-500">*</span>
        </label>
        <input 
          id="email"
          type="email" 
          name="email" 
          value="{{ old('email') }}" 
          required
          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
          placeholder="nama@example.com"
        >
        @error('email')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password Fields --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password <span class="text-red-500">*</span>
          </label>
          <input 
            id="password"
            type="password" 
            name="password" 
            required
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
            placeholder="Minimal 6 karakter"
          >
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
        
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Konfirmasi Password <span class="text-red-500">*</span>
          </label>
          <input 
            id="password_confirmation"
            type="password" 
            name="password_confirmation" 
            required
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
            placeholder="Ulangi password"
          >
        </div>
      </div>

      {{-- Role Field --}}
      <div>
        <label for="roles" class="block text-sm font-medium text-gray-700 mb-2">
          Role/Jabatan <span class="text-red-500">*</span>
        </label>
        <select 
          id="roles"
          name="roles" 
          required
          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm @error('roles') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
        >
          <option value="">Pilih Role</option>
          <option value="admin" {{ old('rolse') === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="staff" {{ old('roles') === 'staff' ? 'selected' : '' }}>Staff</option>
          <option value="user" {{ old('roles') === 'user' ? 'selected' : '' }}>User</option>
        </select>
        @error('roles')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">
          <strong>Admin:</strong> Akses penuh ke semua fitur.
          <strong>Staff:</strong> Akses terbatas ke fitur operasional.
          <strong>User:</strong> Akses dasar untuk penggunaan sistem.
        </p>
      </div>

      {{-- Form Actions --}}
      <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0 pt-6 border-t border-gray-200">
        <a 
          href="{{ route('users.index') }}"
          class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
        >
          Batal
        </a>
        <button 
          type="submit"
          class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
          Tambah User
        </button>
      </div>
    </form>
  </div>

  {{-- Info Card --}}
  <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
        <div class="mt-2 text-sm text-blue-700">
          <ul class="list-disc list-inside space-y-1">
            <li>Password harus minimal 6 karakter</li>
            <li>Email harus unik dan belum terdaftar</li>
            <li>Role menentukan akses pengguna ke fitur sistem</li>
            <li>User baru akan menerima email notifikasi setelah dibuat</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection