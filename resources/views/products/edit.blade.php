@extends('layouts.app')

@section('title', 'Edit Product')
@section('page_title','Product')

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
        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
        <p class="text-gray-600">Update product information</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="bg-white rounded-lg shadow">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
      @csrf
      @method('PUT')

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
                   value="{{ old('name', $product->name) }}"
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
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
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
                      placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
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
                   value="{{ old('brand', $product->brand) }}"
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
                     value="{{ old('size', $product->size) }}"
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
                     value="{{ old('color', $product->color) }}"
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror"
                     placeholder="Enter color">
              @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
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
                     value="{{ old('stock_quantity', $product->stock_quantity) }}"
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
                     value="{{ old('min_stock_level', $product->min_stock_level) }}"
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

      {{-- Current Image --}}
      @if($product->image)
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
          <div class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden">
            <img src="{{ Storage::url($product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover">
          </div>
        </div>
      @endif

      {{-- Image Upload --}}
      <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
          {{ $product->image ? 'Replace Image' : 'Product Image' }}
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
          Update Product
        </button>
      </div>
    </form>
  </div>

  {{-- Danger Zone --}}
  <div class="mt-8 bg-white rounded-lg shadow border border-red-200">
    <div class="px-6 py-4 border-b border-red-200">
      <h3 class="text-lg font-medium text-red-900">Danger Zone</h3>
    </div>
    <div class="p-6">
      <div class="flex items-center justify-between">
        <div>
          <h4 class="text-sm font-medium text-gray-900">Delete Product</h4>
          <p class="text-sm text-gray-500">
            This action cannot be undone. This will permanently delete the product.
            @if($product->stockInItems()->count() > 0 || $product->stockOutItems()->count() > 0)
              <br><span class="text-red-600 font-medium">Cannot delete: Product has stock movements.</span>
            @endif
          </p>
        </div>
        <form action="{{ route('products.destroy', $product) }}" 
              method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
          @csrf
          @method('DELETE')
          <button type="submit" 
                  class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                  {{ ($product->stockInItems()->count() > 0 || $product->stockOutItems()->count() > 0) ? 'disabled' : '' }}>
            Delete Product
          </button>
        </form>
      </div>
    </div>
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