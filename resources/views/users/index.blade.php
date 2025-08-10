@extends('layouts.app')

@section('title', 'User Management')
@section('page_title', 'User Management')

@section('content')
<div x-data="{ 
  showDeleteModal: false, 
  userToDelete: null
}" class="space-y-6">

  {{-- Header with Search and Create Button --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola pengguna sistem</p>
      </div>
      
      {{-- Search and Create Button --}}
      <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        {{-- Search Form --}}
        <form method="GET" action="{{ route('users.index') }}" class="flex-1 sm:flex-none">
          <div class="relative">
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}"
              placeholder="Cari nama atau email..."
              class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
          </div>
        </form>
        
        {{-- Create User Button --}}
        <a 
          href="{{ route('users.create') }}"
          class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors flex items-center gap-2 whitespace-nowrap"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Tambah User
        </a>
      </div>
    </div>
  </div>

  {{-- Users Table --}}
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    @if($users->count() > 0)
      {{-- Table --}}
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @foreach($users as $user)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                      <div class="h-10 w-10 rounded-full bg-gray-900 flex items-center justify-center">
                        <span class="text-white font-medium text-sm">
                          {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                      </div>
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                      <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                       ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst($user->role) }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ $user->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                  {{-- View Button --}}
                  <a href="{{ route('users.show', $user) }}" 
                     class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Lihat
                  </a>
                  
                  {{-- Edit Button --}}
                  <a href="{{ route('users.edit', $user) }}" 
                     class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors">
                    Edit
                  </a>
                  
                  {{-- Delete Button (not for current user) --}}
                  @if($user->id !== auth()->id())
                    <button 
                      @click="userToDelete = {{ $user->toJson() }}; showDeleteModal = true"
                      class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors">
                      Hapus
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($users->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
          {{ $users->links() }}
        </div>
      @endif
    @else
      {{-- Empty State --}}
      <div class="text-center py-12">
        <div class="mx-auto h-12 w-12 text-gray-400">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada user</h3>
        <p class="mt-1 text-sm text-gray-500">
          @if(request('search'))
            Tidak ditemukan user dengan kata kunci "{{ request('search') }}"
          @else
            Mulai dengan menambahkan user baru
          @endif
        </p>
        @if(request('search'))
          <div class="mt-6">
            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
              ← Kembali ke semua user
            </a>
          </div>
        @endif
      </div>
    @endif
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
                  Apakah Anda yakin ingin menghapus user <span x-text="userToDelete?.name" class="font-medium"></span>? 
                  Tindakan ini tidak dapat dibatalkan.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <form method="POST" :action="userToDelete ? `/users/${userToDelete.id}` : ''">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Hapus
            </button>
          </form>
          <button @click="showDeleteModal = false; userToDelete = null" type="button"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection