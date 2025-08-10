@extends('layouts.app')

@section('title','Dashboard')
@section('page_title','Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="bg-white rounded-xl border border-gray-200 p-4">
    <div class="text-sm text-gray-500">Total Users</div>
    <div class="mt-2 text-2xl font-semibold">{{ $totalUsers ?? '-' }}</div>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-4">
    <div class="text-sm text-gray-500">Role Anda</div>
    <div class="mt-2 text-2xl font-semibold capitalize">{{ auth()->user()->roles }}</div>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-4">
    <div class="text-sm text-gray-500">Terakhir Login</div>
    <div class="mt-2 text-2xl font-semibold">{{ optional(auth()->user()->updated_at)->format('d M Y H:i') }}</div>
  </div>
</div>

<div class="mt-6 bg-white rounded-xl border border-gray-200 p-4">
  <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
  <div class="flex gap-3 flex-wrap">
    @if(auth()->user()->role === 'admin')
      <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:opacity-90">Kelola Users</a>
    @endif
    <a href="{{ route('profile') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200">Lihat Profil</a>
  </div>
</div>
@endsection
