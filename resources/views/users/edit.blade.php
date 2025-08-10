@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
<div class="max-w-2xl">

  {{-- Header with Back Button --}}
  <div class="flex items-center space-x-3 mb-6">
    <a href="{{ route('users.show', $user) }}" 
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      Kembali
    </a>
    <h1 class="text-2xl font-semibold text-gray-900">Edit User: {{ $user->name }}</h1>
  </div>

  {{-- Edit User Form --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6 sm:p-8">
    <div class="mb-6">
      <div class="flex items-center space-x-3">
        <div class="h-10 w-10 rounded-full bg-gray-900 flex items-center justify-center">
          <span class="text-white font-medium text-sm">
            {{ strtoupper(substr($user->name, 0, 2)) }}
          </span>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Edit Informasi User</h2>
          <p class="text-sm text-gray-600">{{ $user->email }}</p>
        </div>
      </div>
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

    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
      @csrf
      @method('PUT')
      
      {{-- Name Field --}}
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
          Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input 
          id="name"
          type="text" 
          name="name" 
          value="{{ old('name', $user->name) }}" 
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
          value="{{ old('email', $user->email) }}" 
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
            Password Baru
          </label>
          <input 
            id="password"
            type="password" 
            name="password"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
            placeholder="Kosongkan jika tidak ingin mengubah"
          >
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-sm text-gray-500">Minimal 6 karakter jika diisi</p>
        </div>
        
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Konfirmasi Password Baru
          </label>
          <input 
            id="password_confirmation"
            type="password" 
            name="password_confirmation"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
            placeholder="Ulangi password baru"
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
          <option value="admin" {{ old('roles', $user->roles) === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="staff" {{ old('roles', $user->roles === 'staff' ? 'selected' : '' }}>Staff</option>
          <option value="user" {{ old('roles', $user->roles) === 'user' ? 'selected' : '' }}>User</option>
        </select>
        @error('roles')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">
          Role saat ini: 
          <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
            {{ $user->roles === 'admin' ? 'bg-red-100 text-red-800' : 
               ($user->roles === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
            {{ ucfirst($user->roles) }}
          </span>
        </p>
      </div>

      {{-- Account Info --}}
      <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Informasi Akun</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
          <div>
            <dt class="text-gray-500">Bergabung</dt>
            <dd class="font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Terakhir Update</dt>
            <dd class="font-medium text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</dd>
          </div>
        </dl>
      </div>

      {{-- Form Actions --}}
      <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0 pt-6 border-t border-gray-200">
        <a 
          href="{{ route('users.show', $user) }}"
          class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
        >
          Batal
        </a>
        <button 
          type="submit"
          class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  {{-- Danger Zone --}}
  @if($user->id !== auth()->id())
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 15c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <div class="ml-3 flex-1">
          <h3 class="text-sm font-medium text-red-800">Danger Zone</h3>
          <p class="mt-1 text-sm text-red-700">
            Hapus user ini secara permanen. Tindakan ini tidak dapat dibatalkan.
          </p>
          <div class="mt-4">
            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
              @csrf
              @method('DELETE')
              <button 
                type="submit"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-100 border border-red-300 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus User
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  {{-- Info Card --}}
  <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-medium text-blue-800">Tips</h3>
        <div class="mt-2 text-sm text-blue-700">
          <ul class="list-disc list-inside space-y-1">
            <li>Kosongkan field password jika tidak ingin mengubah password</li>
            <li>Perubahan email akan memerlukan verifikasi ulang</li>
            <li>Perubahan role akan langsung berlaku setelah user login ulang</li>
            <li>Anda tidak dapat menghapus akun Anda sendiri</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection