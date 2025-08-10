@extends('layouts.app')

@section('title','Profil')
@section('page_title','Profil')

@section('content')
<div x-data="{ showEditModal: false }" class="max-w-4xl space-y-6">

  {{-- Profile Header --}}
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-900 to-gray-700 px-6 py-8">
      <div class="flex items-center space-x-6">
        {{-- Avatar --}}
        <div class="h-20 w-20 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30">
          <span class="text-white font-bold text-2xl">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
          </span>
        </div>
        
        {{-- User Info --}}
        <div class="flex-1">
          <h1 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h1>
          <p class="text-gray-300 mt-1">{{ auth()->user()->email }}</p>
          <div class="mt-3">
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
              {{ auth()->user()->roles === 'admin' ? 'bg-red-100 text-red-800' : 
                 (auth()->user()->roles === 'owner' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
              {{ ucfirst(auth()->user()->roles) }}
            </span>
          </div>
        </div>

        {{-- Edit Button --}}
        <div class="hidden sm:block">
          <button 
            @click="showEditModal = true"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-white transition-colors"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Profil
          </button>
        </div>
      </div>
    </div>

    {{-- Profile Details --}}
    <div class="px-6 py-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Akun</h2>
      
      <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
        <div>
          <dt class="text-sm font-medium text-gray-500 mb-2">Nama Lengkap</dt>
          <dd class="text-base font-medium text-gray-900">{{ auth()->user()->name }}</dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500 mb-2">Alamat Email</dt>
          <dd class="text-base font-medium text-gray-900">{{ auth()->user()->email }}</dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500 mb-2">Role/Jabatan</dt>
          <dd>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
              {{ auth()->user()->roles === 'admin' ? 'bg-red-100 text-red-800' : 
                 (auth()->user()->roles === 'owner' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
              {{ ucfirst(auth()->user()->roles) }}
            </span>
          </dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500 mb-2">Status Akun</dt>
          <dd>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
              Aktif
            </span>
          </dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500 mb-2">Bergabung Sejak</dt>
          <dd class="text-base font-medium text-gray-900">
            {{ auth()->user()->created_at->format('d F Y') }}
            <span class="text-sm text-gray-500">({{ auth()->user()->created_at->diffForHumans() }})</span>
          </dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500 mb-2">Terakhir Diupdate</dt>
          <dd class="text-base font-medium text-gray-900">
            {{ auth()->user()->updated_at->format('d F Y, H:i') }}
            <span class="text-sm text-gray-500">({{ auth()->user()->updated_at->diffForHumans() }})</span>
          </dd>
        </div>
      </dl>
    </div>
  </div>

  {{-- Account Security --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Keamanan Akun</h3>
        <p class="text-sm text-gray-600 mt-1">Kelola pengaturan keamanan akun Anda</p>
      </div>
      <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
      </div>
    </div>
    
    <div class="space-y-4">
      <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
        <div class="flex items-center space-x-3">
          <div class="h-8 w-8 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m2-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2.586A2 2 0 008.414 9H11a2 2 0 012 2v4a2 2 0 01-2 2H8.414A2 2 0 007 15.586V18a2 2 0 002 2h6a2 2 0 002-2v-2"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-900">Password</p>
            <p class="text-xs text-gray-500">Terakhir diubah {{ auth()->user()->updated_at->diffForHumans() }}</p>
          </div>
        </div>
        <button 
          @click="showEditModal = true"
          class="text-sm font-medium text-gray-600 hover:text-gray-900"
        >
          Ubah
        </button>
      </div>

      <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
        <div class="flex items-center space-x-3">
          <div class="h-8 w-8 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-900">Email</p>
            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
          </div>
        </div>
        <button 
          @click="showEditModal = true"
          class="text-sm font-medium text-gray-600 hover:text-gray-900"
        >
          Ubah
        </button>
      </div>
    </div>
  </div>

  {{-- Quick Actions --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <a href="{{ route('dashboard') }}" 
         class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
        <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center group-hover:bg-gray-300 transition-colors">
          <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">Dashboard</p>
          <p class="text-xs text-gray-500">Kembali ke dashboard</p>
        </div>
      </a>

      @if(auth()->user()->roles === 'admin')
        <a href="{{ route('users.index') }}" 
           class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
          <div class="h-10 w-10 bg-blue-200 rounded-lg flex items-center justify-center group-hover:bg-blue-300 transition-colors">
            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">User Management</p>
            <p class="text-xs text-gray-500">Kelola pengguna sistem</p>
          </div>
        </a>
      @endif

      <button 
        @click="showEditModal = true"
        class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
        <div class="h-10 w-10 bg-green-200 rounded-lg flex items-center justify-center group-hover:bg-green-300 transition-colors">
          <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">Edit Profil</p>
          <p class="text-xs text-gray-500">Ubah informasi akun</p>
        </div>
      </button>
    </div>
  </div>

  {{-- Mobile Edit Button --}}
  <div class="sm:hidden">
    <button 
      @click="showEditModal = true"
      class="w-full flex justify-center items-center px-4 py-3 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors"
    >
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
      </svg>
      Edit Profil
    </button>
  </div>

  {{-- Edit Profile Modal --}}
  <div x-show="showEditModal" 
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-50 overflow-y-auto" 
       style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form method="POST" action="#">
          @csrf
          @method('PUT')
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Profil</h3>
                
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" required
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password"
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 sm:ml-3 sm:w-auto sm:text-sm">
              Simpan Perubahan
            </button>
            <button @click="showEditModal = false" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection
