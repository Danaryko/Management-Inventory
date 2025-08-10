@extends('layouts.app')

@section('title', 'Detail User')
@section('page_title', 'User Management')

@section('content')
<div x-data="{ showDeleteModal: false }" class="space-y-6">

  {{-- Header with Back Button --}}
  <div class="flex items-center justify-between">
    <div class="flex items-center space-x-3">
      <a href="{{ route('users.index') }}" 
         class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
      </a>
      <h1 class="text-2xl font-semibold text-gray-900">Detail User</h1>
    </div>
    
    {{-- Action Buttons --}}
    <div class="flex space-x-3">
      <a href="{{ route('users.edit', $user) }}"
         class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-transparent rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Edit
      </a>
      
      @if($user->id !== auth()->id())
        <button @click="showDeleteModal = true"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 border border-transparent rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          Hapus
        </button>
      @endif
    </div>
  </div>

  {{-- User Information Card --}}
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
      <div class="flex items-center space-x-4">
        {{-- Avatar --}}
        <div class="h-16 w-16 rounded-full bg-gray-900 flex items-center justify-center">
          <span class="text-white font-medium text-xl">
            {{ strtoupper(substr($user->name, 0, 2)) }}
          </span>
        </div>
        
        {{-- Basic Info --}}
        <div>
          <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
          <p class="text-sm text-gray-600">{{ $user->email }}</p>
          <div class="mt-1">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
              {{ $user->roles === 'admin' ? 'bg-red-100 text-red-800' : 
                 ($user->roles === 'owner' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
              {{ ucfirst($user->roles) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    {{-- Detailed Information --}}
    <div class="px-6 py-6">
      <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        <div>
          <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500">Email</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500">Role</dt>
          <dd class="mt-1">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
              {{ $user->roles === 'admin' ? 'bg-red-100 text-red-800' : 
                 ($user->roles === 'owner' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
              {{ ucfirst($user->roles) }}
            </span>
          </dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500">Status</dt>
          <dd class="mt-1">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
              Aktif
            </span>
          </dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500">Bergabung</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</dd>
        </div>
        
        <div>
          <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</dd>
        </div>
      </dl>
    </div>
  </div>

  {{-- Account Activity Card --}}
  <div class="bg-white rounded-xl border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-medium text-gray-900">Aktivitas Akun</h3>
    </div>
    <div class="px-6 py-4">
      <div class="space-y-4">
        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
          <div class="flex items-center space-x-3">
            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
              <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900">Akun dibuat</p>
              <p class="text-xs text-gray-500">{{ $user->created_at->format('d F Y, H:i') }}</p>
            </div>
          </div>
        </div>
        
        @if($user->updated_at != $user->created_at)
          <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
            <div class="flex items-center space-x-3">
              <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">Profil diperbarui</p>
                <p class="text-xs text-gray-500">{{ $user->updated_at->format('d F Y, H:i') }}</p>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Delete Confirmation Modal --}}
  <div x-show="showDeleteModal" 
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
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 15c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Hapus User</h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Apakah Anda yakin ingin menghapus user <span class="font-medium">{{ $user->name }}</span>? 
                  Tindakan ini tidak dapat dibatalkan.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <form method="POST" action="{{ route('users.destroy', $user) }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Hapus
            </button>
          </form>
          <button @click="showDeleteModal = false" type="button"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection