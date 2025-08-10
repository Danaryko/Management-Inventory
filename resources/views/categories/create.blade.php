@extends('layouts.app')

@section('title', 'Create Category')
@section('page_title','Category')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('categories.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Category</h1>
        <p class="text-gray-600">Add a new product category</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="bg-white rounded-lg shadow">
    <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-6">
      @csrf

      {{-- Name --}}
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
          Category Name *
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="{{ old('name') }}"
               required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
               placeholder="Enter category name">
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Description --}}
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
          Description
        </label>
        <textarea id="description" 
                  name="description" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                  placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
        @error('description')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex justify-end gap-3">
        <a href="{{ route('categories.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Cancel
        </a>
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Create Category
        </button>
      </div>
    </form>
  </div>
</div>
@endsection