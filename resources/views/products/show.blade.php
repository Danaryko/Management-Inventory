@extends('layouts.app')

@section('title', 'Product Details')
@section('page_title', content: 'Product')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
  {{-- Header --}}
  @if(auth()->user()->roles === 'operator')
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('products.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
        <p class="text-gray-600">Product details and information</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('products.edit', $product) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Edit
        </a>
      </div>
    </div>
  </div>
  @endif

  {{-- Owner --}}
  @if(auth()->user()->roles === 'owner' || auth()->user()->roles === 'admin')
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('products.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
        <p class="text-gray-600">Product details and information</p>
      </div>
      <div class="flex gap-2">
      </div>
    </div>
  </div>
  @endif

  <div class="grid lg:grid-cols-3 gap-6">
    {{-- Product Image --}}
    <div class="lg:col-span-1">
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h2>
        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
          @if($product->image)
            <img src="{{ Storage::url($product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Product Information --}}
    <div class="lg:col-span-2 space-y-6">
      {{-- Basic Information --}}
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
        <dl class="grid md:grid-cols-2 gap-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">Product Name</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->name }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Category</dt>
            <dd class="mt-1">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $product->category->name }}
              </span>
            </dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">SKU</dt>
            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $product->sku }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Brand</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->brand ?: 'Not specified' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Size</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->size ?: 'Not specified' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Color</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->color ?: 'Not specified' }}</dd>
          </div>
          <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Description</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->description ?: 'No description available' }}</dd>
          </div>
        </dl>
      </div>

      {{-- Pricing & Stock --}}
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing & Stock</h2>
        <dl class="grid md:grid-cols-3 gap-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">Price</dt>
            <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($product->price, 2) }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
            <dd class="mt-1">
              <span class="text-lg font-semibold {{ $product->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                {{ $product->stock_quantity }} units
              </span>
              @if($product->isLowStock())
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  Low Stock
                </span>
              @endif
            </dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Min Stock Level</dt>
            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $product->min_stock_level }} units</dd>
          </div>
        </dl>
      </div>

      {{-- Metadata --}}
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h2>
        <dl class="grid md:grid-cols-2 gap-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">Created</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('M d, Y H:i') }}</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>
</div>
@endsection