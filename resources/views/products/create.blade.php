@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('products.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Product</h1>
        <p class="text-gray-600">Add a new product to your inventory</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="bg-white rounded-lg shadow">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
      @csrf

      <div class="grid md:grid-cols-2 gap-6">
        {{-- Basic Information --}}
        <div class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
          
          {{-- Name --}}
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Product Name *
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                   placeholder="Enter product name">
            @error('name')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Category --}}
          <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
              Category *
            </label>
            <select name="category_id" 
                    id="category_id"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
              <option value="">Select Category</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- SKU --}}
          <div>
            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
              SKU *
            </label>
            <input type="text" 
                   id="sku" 
                   name="sku" 
                   value="{{ old('sku') }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror"
                   placeholder="Enter SKU">
            @error('sku')
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
                      placeholder="Enter product description">{{ old('description') }}</textarea>
            @error('description')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Product Details --}}
        <div class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900">Product Details</h3>
          
          {{-- Brand --}}
          <div>
            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
              Brand
            </label>
            <input type="text" 
                   id="brand" 
                   name="brand" 
                   value="{{ old('brand') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('brand') border-red-500 @enderror"
                   placeholder="Enter brand">
            @error('brand')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Size & Color --}}
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                Size
              </label>
              <input type="text" 
                     id="size" 
                     name="size" 
                     value="{{ old('size') }}"
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('size') border-red-500 @enderror"
                     placeholder="Enter size">
              @error('size')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                Color
              </label>
              <input type="text" 
                     id="color" 
                     name="color" 
                     value="{{ old('color') }}"
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror"
                     placeholder="Enter color">
              @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Price --}}
          <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
              Price *
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">$</span>
              </div>
              <input type="number" 
                     id="price" 
                     name="price" 
                     value="{{ old('price') }}"
                     step="0.01"
                     min="0"
                     required
                     class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                     placeholder="0.00">
            </div>
            @error('price')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Stock Information --}}
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                Stock Quantity *
              </label>
              <input type="number" 
                     id="stock_quantity" 
                     name="stock_quantity" 
                     value="{{ old('stock_quantity', 0) }}"
                     min="0"
                     required
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-500 @enderror"
                     placeholder="0">
              @error('stock_quantity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="min_stock_level" class="block text-sm font-medium text-gray-700 mb-2">
                Min Stock Level *
              </label>
              <input type="number" 
                     id="min_stock_level" 
                     name="min_stock_level" 
                     value="{{ old('min_stock_level', 5) }}"
                     min="0"
                     required
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_stock_level') border-red-500 @enderror"
                     placeholder="5">
              @error('min_stock_level')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Image Upload --}}
      <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
          Product Image
        </label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
          <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600">
              <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                <span>Upload a file</span>
                <input id="image" name="image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
              </label>
              <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
          </div>
        </div>
        <div id="image-preview" class="mt-4 hidden">
          <img id="preview-img" class="h-32 w-32 object-cover rounded-lg" src="#" alt="Preview">
        </div>
        @error('image')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex justify-end gap-3">
        <a href="{{ route('products.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Cancel
        </a>
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Create Product
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function previewImage(input) {
  const preview = document.getElementById('image-preview');
  const previewImg = document.getElementById('preview-img');
  
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
      previewImg.src = e.target.result;
      preview.classList.remove('hidden');
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection