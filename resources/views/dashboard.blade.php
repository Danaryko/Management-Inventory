@extends('layouts.app')

@section('title','Dashboard')
@section('page_title','Dashboard')

@section('content')
<div class="space-y-6">

  {{-- Welcome Section --}}
  <div class="bg-gradient-to-r from-gray-900 to-gray-700 rounded-xl text-white p-6 sm:p-8">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold mb-2">
          Selamat datang, {{ auth()->user()->name }}!
        </h2>
        <p class="text-gray-300 text-sm sm:text-base">
          Kelola sistem inventory Anda dengan mudah dan efisien
        </p>
      </div>
      <div class="hidden sm:block">
        <div class="h-16 w-16 bg-white/10 rounded-full flex items-center justify-center">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Stats Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Total Users Card --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
          <p class="text-3xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
          <p class="text-sm text-green-600 mt-2">
            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            Aktif
          </p>
        </div>
        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
        </div>
      </div>
    </div>

    {{-- Your Role Card --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500 mb-1">Role Anda</p>
          <p class="text-3xl font-bold text-gray-900 capitalize">{{ auth()->user()->role }}</p>
          <div class="mt-2">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
              {{ auth()->user()->role === 'admin' ? 'bg-red-100 text-red-800' : 
                 (auth()->user()->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
              {{ ucfirst(auth()->user()->role) }}
            </span>
          </div>
        </div>
        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
          <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
      </div>
    </div>

    {{-- Last Login Card --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500 mb-1">Terakhir Login</p>
          <p class="text-2xl font-bold text-gray-900">
            {{ optional(auth()->user()->updated_at)->format('d M') ?? 'N/A' }}
          </p>
          <p class="text-sm text-gray-600 mt-2">
            {{ optional(auth()->user()->updated_at)->format('Y, H:i') ?? 'Tidak tersedia' }}
          </p>
        </div>
        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
          <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Quick Actions Section --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        <p class="text-sm text-gray-600">Akses cepat ke fitur-fitur utama</p>
      </div>
      <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      {{-- View Profile --}}
      <a href="{{ route('profile') }}" 
         class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
        <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center group-hover:bg-gray-300 transition-colors">
          <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">Lihat Profil</p>
          <p class="text-xs text-gray-500">Kelola informasi akun</p>
        </div>
      </a>

      @if(auth()->user()->role === 'admin')
        {{-- Manage Users --}}
        <a href="{{ route('users.index') }}" 
           class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
          <div class="h-10 w-10 bg-blue-200 rounded-lg flex items-center justify-center group-hover:bg-blue-300 transition-colors">
            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">Kelola Users</p>
            <p class="text-xs text-gray-500">Admin user management</p>
          </div>
        </a>

        {{-- System Reports (placeholder) --}}
        <a href="#" 
           class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group relative">
          <div class="h-10 w-10 bg-purple-200 rounded-lg flex items-center justify-center group-hover:bg-purple-300 transition-colors">
            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">System Reports</p>
            <p class="text-xs text-gray-500">Analytics & insights</p>
          </div>
          <span class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
            Soon
          </span>
        </a>
      @endif

      {{-- Inventory (placeholder) --}}
      <a href="#" 
         class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group relative">
        <div class="h-10 w-10 bg-green-200 rounded-lg flex items-center justify-center group-hover:bg-green-300 transition-colors">
          <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">Inventory Items</p>
          <p class="text-xs text-gray-500">Manage stock & items</p>
        </div>
        <span class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
          Soon
        </span>
      </a>

      {{-- Settings (placeholder) --}}
      <a href="#" 
         class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors group relative">
        <div class="h-10 w-10 bg-orange-200 rounded-lg flex items-center justify-center group-hover:bg-orange-300 transition-colors">
          <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">System Settings</p>
          <p class="text-xs text-gray-500">Configure preferences</p>
        </div>
        <span class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
          Soon
        </span>
      </a>

      {{-- Help & Support --}}
      <a href="#" 
         class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors group">
        <div class="h-10 w-10 bg-red-200 rounded-lg flex items-center justify-center group-hover:bg-red-300 transition-colors">
          <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">Help & Support</p>
          <p class="text-xs text-gray-500">Get assistance</p>
        </div>
      </a>
    </div>
  </div>

  {{-- Recent Activity (placeholder) --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        <p class="text-sm text-gray-600">Aktivitas terbaru dalam sistem</p>
      </div>
    </div>
    
    <div class="space-y-4">
      <div class="flex items-center p-4 bg-gray-50 rounded-lg">
        <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
          <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <div class="ml-4 flex-1">
          <p class="text-sm font-medium text-gray-900">Account Login</p>
          <p class="text-xs text-gray-500">You successfully logged into your account</p>
        </div>
        <div class="text-xs text-gray-400">
          {{ now()->format('H:i') }}
        </div>
      </div>

      <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No more activity</h3>
        <p class="mt-1 text-sm text-gray-500">Activity tracking will be added in future updates.</p>
      </div>
    </div>
  </div>

</div>
@endsection
