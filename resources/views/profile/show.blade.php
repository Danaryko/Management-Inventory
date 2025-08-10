@extends('layouts.app')

@section('title','Profil')
@section('page_title','Profil')

@section('content')
<div class="max-w-2xl">
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <h2 class="text-xl font-semibold mb-4">Profil</h2>

    <dl class="divide-y divide-gray-200">
      <div class="py-3 grid grid-cols-3 gap-4">
        <dt class="text-gray-500">Nama</dt>
        <dd class="col-span-2 font-medium">{{ auth()->user()->name }}</dd>
      </div>
      <div class="py-3 grid grid-cols-3 gap-4">
        <dt class="text-gray-500">Email</dt>
        <dd class="col-span-2 font-medium">{{ auth()->user()->email }}</dd>
      </div>
      <div class="py-3 grid grid-cols-3 gap-4">
        <dt class="text-gray-500">Role</dt>
        <dd class="col-span-2 font-medium capitalize">{{ auth()->user()->roles }}</dd>
      </div>
    </dl>
  </div>
</div>
@endsection
