@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="bg-white py-8 px-6 shadow-lg rounded-lg">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Buat akun baru
        </h2>
    </div>

    <div class="mt-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terdapat beberapa kesalahan:
                        </h3>
                        <div class="mt-2">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form class="space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Nama Lengkap
                </label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           value="{{ old('name') }}"
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Masukkan nama lengkap Anda">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email
                </label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           value="{{ old('email') }}"
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Masukkan email Anda">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Masukkan password Anda">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>
                <div class="mt-1">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Konfirmasi password Anda">
                </div>
            </div>

            <div>
                <label for="roles" class="block text-sm font-medium text-gray-700">
                    Role
                </label>
                <div class="mt-1">
                    <select id="roles" name="roles" required 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('roles') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operator" {{ old('roles') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="owner" {{ old('roles') == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                    </span>
                    Daftar
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                        Masuk sekarang
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection