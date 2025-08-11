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
          @switch(auth()->user()->roles)
            @case('admin')
              Kelola seluruh sistem dan pengguna dengan akses penuh
              @break
            @case('owner')
              Pantau bisnis dan analisis performa inventory Anda
              @break
            @case('manager')
              Kelola tim dan operasional departemen Anda
              @break
            @case('operator')
              Kelola inventory dan operasional harian Anda
              @break
            @default
              Kelola sistem inventory Anda dengan mudah dan efisien
          @endswitch
        </p>
      </div>
      <div class="hidden sm:block">
        <div class="h-16 w-16 bg-white/10 rounded-full flex items-center justify-center">
          @switch(auth()->user()->roles)
            @case('admin')
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
              @break
            @case('owner')
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              @break
            @case('manager')
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              @break
            @default
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
          @endswitch
        </div>
      </div>
    </div>
  </div>

  {{-- Role-based Stats Cards --}}
  @if(auth()->user()->roles === 'admin')
    {{-- Admin Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
            <p class="text-sm text-green-600 mt-2">
              <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              System Active
            </p>
          </div>
          <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Products</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
            <p class="text-sm text-blue-600 mt-2">
              <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
              In Inventory
            </p>
          </div>
          <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Activities</p>
            <p class="text-3xl font-bold text-gray-900">{{ $todayActivities ?? 0 }}</p>
            <p class="text-sm text-purple-600 mt-2">
              <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              System Actions
            </p>
          </div>
          <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Your Role</p>
            <p class="text-3xl font-bold text-gray-900 capitalize">{{ auth()->user()->roles }}</p>
            <div class="mt-2">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                Administrator
              </span>
            </div>
          </div>
          <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>
  @elseif(auth()->user()->roles === 'owner')
    {{-- Owner Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Monthly Operations</p>
            <p class="text-3xl font-bold text-gray-900">{{ isset($businessStats) ? $businessStats['monthly_stock_ins'] + $businessStats['monthly_stock_outs'] : 0 }}</p>
            <p class="text-sm text-blue-600 mt-2">In/Out Transactions</p>
          </div>
          <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Business Role</p>
            <p class="text-3xl font-bold text-gray-900 capitalize">{{ auth()->user()->roles }}</p>
            <div class="mt-2">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                Business Owner
              </span>
            </div>
          </div>
          <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    {{-- Stock In/Out Chart Section - Owner Only --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Stock Trends</h3>
          <p class="text-sm text-gray-600">Stock In vs Stock Out over the last 6 months</p>
        </div>
        <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
        </div>
      </div>
      
      <div class="relative">
        <canvas id="stockChart" class="w-full" style="height: 400px;"></canvas>
      </div>
      
      <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-green-50 rounded-lg p-4">
          <div class="flex items-center">
            <div class="h-3 w-3 bg-green-500 rounded-full mr-2"></div>
            <p class="text-sm font-medium text-gray-900">Total Stock In</p>
          </div>
          <p class="text-2xl font-bold text-green-600 mt-1">
            {{ isset($businessStats) ? $businessStats['monthly_stock_ins'] : 0 }}
          </p>
          <p class="text-xs text-gray-500 mt-1">This month</p>
        </div>
        
        <div class="bg-red-50 rounded-lg p-4">
          <div class="flex items-center">
            <div class="h-3 w-3 bg-red-500 rounded-full mr-2"></div>
            <p class="text-sm font-medium text-gray-900">Total Stock Out</p>
          </div>
          <p class="text-2xl font-bold text-red-600 mt-1">
            {{ isset($businessStats) ? $businessStats['monthly_stock_outs'] : 0 }}
          </p>
          <p class="text-xs text-gray-500 mt-1">This month</p>
        </div>
      </div>
    </div>
  @elseif(auth()->user()->roles === 'manager, admin')
    {{-- Manager Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Team Members</p>
            <p class="text-3xl font-bold text-gray-900">{{ isset($teamStats) ? $teamStats['team_members'] : 0 }}</p>
            <p class="text-sm text-blue-600 mt-2">Under Management</p>
          </div>
          <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Activities</p>
            <p class="text-3xl font-bold text-gray-900">{{ isset($teamStats) ? $teamStats['today_activities'] : 0 }}</p>
            <p class="text-sm text-green-600 mt-2">Team Performance</p>
          </div>
          <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Management Role</p>
            <p class="text-3xl font-bold text-gray-900 capitalize">{{ auth()->user()->roles }}</p>
            <div class="mt-2">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                Team Manager
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
    </div>
  @else
    {{-- Operator/Staff Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">My Activities</p>
            <p class="text-3xl font-bold text-gray-900">{{ $myActivities ?? 0 }}</p>
            <p class="text-sm text-green-600 mt-2">
              <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              Total Completed
            </p>
          </div>
          <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Role Anda</p>
            <p class="text-3xl font-bold text-gray-900 capitalize">{{ auth()->user()->roles }}</p>
            <div class="mt-2">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                {{ auth()->user()->roles === 'admin' ? 'bg-red-100 text-red-800' : 
                   (auth()->user()->roles === 'owner' ? 'bg-blue-100 text-blue-800' : 
                   (auth()->user()->roles === 'manager' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')) }}">
                {{ ucfirst(auth()->user()->roles) }}
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
  @endif

  {{-- Role-based Quick Actions Section --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        <p class="text-sm text-gray-600">
          @switch(auth()->user()->roles)
            @case('admin')
              Administrative controls and system management
              @break
            @case('owner')
              Business oversight and strategic actions
              @break
            @case('manager')
              Team management and operational controls
              @break
            @default
              Akses cepat ke fitur-fitur utama
          @endswitch
        </p>
      </div>
      <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      {{-- Common Action: View Profile --}}
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

      @if(isset($quickActions))
        @foreach($quickActions as $action)
          <a href="{{ $action['url'] }}" 
             class="flex items-center p-4 bg-{{ $action['color'] }}-50 rounded-lg hover:bg-{{ $action['color'] }}-100 transition-colors group">
            <div class="h-10 w-10 bg-{{ $action['color'] }}-200 rounded-lg flex items-center justify-center group-hover:bg-{{ $action['color'] }}-300 transition-colors">
              @switch($action['icon'])
                @case('users')
                  <svg class="h-5 w-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                  @break
                @case('document-text')
                  <svg class="h-5 w-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  @break
                @case('chart-bar')
                  <svg class="h-5 w-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                  @break
                @case('cube')
                  <svg class="h-5 w-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  @break
                @case('plus')
                  <svg class="h-5 w-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  @break
                @default
                  <svg class="h-5 w-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
              @endswitch
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">{{ $action['title'] }}</p>
              <p class="text-xs text-gray-500">{{ $action['description'] ?? 'Quick access' }}</p>
            </div>
          </a>
        @endforeach
      @else
        {{-- Fallback actions based on role --}}
        @if(auth()->user()->roles === 'admin')
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

          <a href="{{ route('activities.index') }}" 
             class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
            <div class="h-10 w-10 bg-green-200 rounded-lg flex items-center justify-center group-hover:bg-green-300 transition-colors">
              <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">Activity Logs</p>
              <p class="text-xs text-gray-500">System activity history</p>
            </div>
          </a>
        @endif
      @endif
    </div>
  </div>

  {{-- Recent Activity Section --}}
  @if(auth()->user()->roles === 'admin')
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        <p class="text-sm text-gray-600">
          @if(auth()->user()->roles === 'operator')
            Your recent activities in the system
          @else
            Latest activities in the system
          @endif
        </p>
      </div>
      @if(in_array(auth()->user()->roles, ['admin', 'operator']))
        <a href="{{ route('activities.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
          View All →
        </a>
      @endif
    </div>
    
    <div class="space-y-4">
      @if(isset($recentActivities) && $recentActivities->count() > 0)
        @foreach($recentActivities->take(5) as $activity)
          <div class="flex items-center p-4 bg-gray-50 rounded-lg">
            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <div class="ml-4 flex-1">
              <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $activity->action)) }}</p>
              <p class="text-xs text-gray-500">{{ $activity->description }} by {{ $activity->user->name ?? 'System' }}</p>
            </div>
            <div class="text-xs text-gray-400">
              {{ $activity->created_at->diffForHumans() }}
            </div>
          </div>
        @endforeach
      @else
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
          <p class="mt-1 text-sm text-gray-500">
            @if(auth()->user()->roles === 'operator')
              Your activity will appear here as you use the system.
            @else
              Recent system activities will appear here.
            @endif
          </p>
        </div>
      @endif
    </div>
  </div>
  @endif

</div>
@endsection

@if(auth()->user()->roles === 'owner')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('stockChart');
  if (!ctx) return;

  const raw = @json($chartData ?? []);
  const inArr  = (raw.datasets?.[0]?.data || []).map(Number);
  const outArr = (raw.datasets?.[1]?.data || []).map(Number);

  const totalIn  = inArr.reduce((a,b)=>a+(b||0), 0);
  const totalOut = outArr.reduce((a,b)=>a+(b||0), 0);

  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Stock In', 'Stock Out'],
      datasets: [{
        data: [totalIn, totalOut],
        backgroundColor: ['rgb(34, 197, 94)', 'rgb(239, 68, 68)'], // hijau & merah
        borderColor: '#ffffff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: 'Distribusi Stock In vs Stock Out (6 bulan terakhir)'
        },
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const value = ctx.raw ?? 0;
              const total = (ctx.dataset.data || []).reduce((a,b)=>a+(b||0), 0);
              const pct = total ? ((value/total)*100).toFixed(1) : 0;
              return `${ctx.label}: ${value} (${pct}%)`;
            }
          }
        }
      }
    }
  });
});
</script>
@endpush
@endif

