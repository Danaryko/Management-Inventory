@extends('layouts.app')

@section('title', 'Products')
@section('page_title','Product')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
  {{-- Header --}}
  @if(auth()->user()->roles === 'operator')
  <div class="mb-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Products</h1>
        <p class="text-gray-600">Manage your product inventory</p>
      </div>
      <a href="{{ route('products.create') }}" 
         class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Product
      </a>
    </div>
  </div>
  @endif

  @if(auth()->user()->roles === 'owner' || auth()->user()->roles === 'admin')
  <div class="mb-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Products</h1>
        <p class="text-gray-600">Manage your product inventory</p>
      </div>
    </div>
  </div>
  @endif

  {{-- Filters --}}
  <div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" action="{{ route('products.index') }}" class="grid md:grid-cols-4 gap-4">
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
        <input type="text" 
               id="search"
               name="search" 
               value="{{ request('search') }}"
               placeholder="Search products..." 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
      </div>
      
      <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <select name="category_id" 
                id="category_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">All Categories</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex items-end">
        <label class="flex items-center">
          <input type="checkbox" 
                 name="low_stock" 
                 value="1" 
                 {{ request('low_stock') ? 'checked' : '' }}
                 class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
          <span class="ml-2 text-sm text-gray-600">Low Stock Only</span>
        </label>
      </div>

      <div class="flex items-end gap-2">
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Filter
        </button>
        @if(request()->hasAny(['search', 'category_id', 'low_stock']))
          <a href="{{ route('products.index') }}" 
             class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Clear
          </a>
        @endif
      </div>
    </form>
  </div>

  {{-- Products Grid --}}
  @if(auth()->user()->roles === 'operator')
  <div class="bg-white rounded-lg shadow">
    @if($products->count() > 0)
      <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
        @foreach($products as $product)
          <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
              @if($product->image)
                <img src="{{ Storage::url($product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-48 object-cover">
              @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                  <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                </div>
              @endif
            </div>
            
            <div class="p-4">
              <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                @if($product->isLowStock())
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Low Stock
                  </span>
                @endif
              </div>
              
              <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
              
              <div class="space-y-1 text-xs text-gray-600">
                <div class="flex justify-between">
                  <span>SKU:</span>
                  <span class="font-medium">{{ $product->sku }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Price:</span>
                  <span class="font-medium">${{ number_format($product->price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Stock:</span>
                  <span class="font-medium {{ $product->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                    {{ $product->stock_quantity }} units
                  </span>
                </div>
              </div>
              
              <div class="mt-4 flex gap-2">
                <a href="{{ route('products.show', $product) }}" 
                   class="flex-1 text-center px-3 py-1 text-xs border border-gray-300 rounded hover:bg-gray-50">
                  View
                </a>
                <a href="{{ route('products.edit', $product) }}" 
                   class="flex-1 text-center px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                  Edit
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $products->appends(request()->query())->links() }}
        </div>
      @endif
    @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
        <div class="mt-6">
          <a href="{{ route('products.create') }}" 
             class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Product
          </a>
        </div>
      </div>
    @endif
  </div>
  @endif

  {{-- Products Grid for owner --}}
  @if(auth()->user()->roles === 'owner' || auth()->user()->roles === 'admin')
  <div class="bg-white rounded-lg shadow">
    @if($products->count() > 0)
      <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
        @foreach($products as $product)
          <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
              @if($product->image)
                <img src="{{ Storage::url($product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-48 object-cover">
              @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                  <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                </div>
              @endif
            </div>
            
            <div class="p-4">
              <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                @if($product->isLowStock())
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Low Stock
                  </span>
                @endif
              </div>
              
              <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
              
              <div class="space-y-1 text-xs text-gray-600">
                <div class="flex justify-between">
                  <span>SKU:</span>
                  <span class="font-medium">{{ $product->sku }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Price:</span>
                  <span class="font-medium">${{ number_format($product->price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Stock:</span>
                  <span class="font-medium {{ $product->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                    {{ $product->stock_quantity }} units
                  </span>
                </div>
              </div>
              
              <div class="mt-4 flex gap-2">
                <a href="{{ route('products.show', $product) }}" 
                   class="flex-1 text-center px-3 py-1 text-xs border bg-blue-600 text-white rounded hover:bg-blue-700">
                  View
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $products->appends(request()->query())->links() }}
        </div>
      @endif
    @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
        <div class="mt-6">
          <a href="{{ route('products.create') }}" 
             class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Product
          </a>
        </div>
      </div>
    @endif
  </div>
  @endif

</div>
@endsection