@extends('layouts.app')

@section('title', 'Create Supplier')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('suppliers.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Supplier</h1>
        <p class="text-gray-600">Add a new supplier to your network</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="bg-white rounded-lg shadow">
    <form action="{{ route('suppliers.store') }}" method="POST" class="p-6 space-y-6">
      @csrf

      {{-- Company Name --}}
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
          Company Name *
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="{{ old('name') }}"
               required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
               placeholder="Enter company name">
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Contact Person --}}
      <div>
        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
          Contact Person
        </label>
        <input type="text" 
               id="contact_person" 
               name="contact_person" 
               value="{{ old('contact_person') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_person') border-red-500 @enderror"
               placeholder="Enter contact person name">
        @error('contact_person')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Contact Information --}}
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email
          </label>
          <input type="email" 
                 id="email" 
                 name="email" 
                 value="{{ old('email') }}"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                 placeholder="supplier@example.com">
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            Phone
          </label>
          <input type="tel" 
                 id="phone" 
                 name="phone" 
                 value="{{ old('phone') }}"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                 placeholder="+1 234 567 8900">
          @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Address --}}
      <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
          Address
        </label>
        <textarea id="address" 
                  name="address" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                  placeholder="Enter complete address">{{ old('address') }}</textarea>
        @error('address')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex justify-end gap-3">
        <a href="{{ route('suppliers.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Cancel
        </a>
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Create Supplier
        </button>
      </div>
    </form>
  </div>
</div>
@endsection