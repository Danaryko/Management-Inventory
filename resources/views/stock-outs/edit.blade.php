@extends('layouts.app')

@section('title', 'Edit Stock Out')
@section('page_title', 'Stock Out')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('stock-outs.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Stock Out</h1>
        <p class="text-gray-600">Modify stock out transaction: {{ $stockOut->reference_number }}</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="bg-white rounded-lg shadow">
    <form action="{{ route('stock-outs.update', $stockOut) }}" method="POST" class="p-6 space-y-6" x-data="stockOutEditForm()">
      @csrf
      @method('PUT')

      {{-- Basic Information --}}
      <div class="grid md:grid-cols-2 gap-6">
        {{-- Reference Number --}}
        <div>
          <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
            Reference Number *
          </label>
          <input type="text" 
                 id="reference_number" 
                 name="reference_number" 
                 value="{{ old('reference_number', $stockOut->reference_number) }}"
                 required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('reference_number') border-red-500 @enderror">
          @error('reference_number')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Date --}}
        <div>
          <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
            Stock Out Date *
          </label>
          <input type="date" 
                 id="date" 
                 name="date" 
                 value="{{ old('date', $stockOut->date->format('Y-m-d')) }}"
                 required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('date') border-red-500 @enderror">
          @error('date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Notes --}}
      <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
          Notes
        </label>
        <textarea id="notes" 
                  name="notes" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('notes') border-red-500 @enderror"
                  placeholder="Enter any additional notes (purpose: sale, damaged, expired, etc.)">{{ old('notes', $stockOut->notes) }}</textarea>
        @error('notes')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Items Section --}}
      <div>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">Items</h3>
          <button type="button" 
                  @click="addItem()"
                  class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Item
          </button>
        </div>

        @error('items')
          <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ $message }}
          </div>
        @enderror

        {{-- Items Table --}}
        <div class="overflow-x-auto">
          <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white">
              <template x-for="(item, index) in items" :key="index">
                <tr class="border-t border-gray-200">
                  <td class="px-4 py-3">
                    <select :name="`items[${index}][product_id]`" 
                            x-model="item.product_id"
                            @change="updateProductInfo(index)"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                      <option value="">Select Product</option>
                      @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-name="{{ $product->name }}"
                                data-stock="{{ $product->stock_quantity }}">
                          {{ $product->name }}
                        </option>
                      @endforeach
                    </select>
                    <span x-show="item.product_name" 
                          x-text="item.product_name" 
                          class="text-sm text-gray-500 mt-1 block"></span>
                  </td>
                  <td class="px-4 py-3">
                    <span x-text="item.current_stock >= 0 ? item.current_stock : '-'" 
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="item.current_stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"></span>
                  </td>
                  <td class="px-4 py-3">
                    <input type="number" 
                           :name="`items[${index}][quantity]`"
                           x-model.number="item.quantity"
                           @input="calculateSubtotal(index)"
                           min="1"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Qty">
                    <div x-show="item.quantity > item.available_stock && item.available_stock >= 0" 
                         class="text-xs text-red-600 mt-1">
                      May cause negative stock
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <div class="text-sm font-medium" x-text="formatCurrency(item.subtotal)"></div>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <button type="button" 
                            @click="removeItem(index)"
                            class="text-red-600 hover:text-red-900">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </td>
                </tr>
              </template>
              
              {{-- Empty state --}}
              <tr x-show="items.length === 0">
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                  No items added yet. Click "Add Item" to get started.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
        <a href="{{ route('stock-outs.index') }}" 
           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Cancel
        </a>
        <button type="submit" 
                :disabled="items.length === 0"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg">
          Update Stock Out
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function stockOutEditForm() {
  return {
    items: [],
    originalItems: [], // Track original quantities for stock calculation
    
    addItem() {
      this.items.push({
        product_id: '',
        product_name: '',
        current_stock: 0,
        available_stock: 0,
        original_quantity: 0,
        quantity: 1,
      });
    },
    
    removeItem(index) {
      this.items.splice(index, 1);
    },
    
    updateProductInfo(index) {
      const select = document.querySelector(`select[name="items[${index}][product_id]"]`);
      const selectedOption = select.options[select.selectedIndex];
      
      if (selectedOption.value) {
        const currentStock = parseInt(selectedOption.dataset.stock) || 0;
        const originalQuantity = this.items[index].original_quantity || 0;
        
        this.items[index].product_name = selectedOption.dataset.name;
        this.items[index].current_stock = currentStock;
        // Available stock = current stock + original quantity (since original was subtracted)
        this.items[index].available_stock = currentStock + originalQuantity;
      } else {
        this.items[index].product_name = '';
        this.items[index].current_stock = 0;
        this.items[index].available_stock = 0;
      }
      
      this.calculateSubtotal(index);
    },
    
    init() {
      // Load existing items
      @foreach($stockOut->items as $item)
        @php
          $product = $item->product;
          $currentStock = $product->stock_quantity;
          $originalQuantity = $item->quantity;
        @endphp
        this.items.push({
          product_id: '{{ $item->product_id }}',
          product_name: '{{ $product->name }}',
          current_stock: {{ $currentStock }},
          available_stock: {{ $currentStock + $originalQuantity }}, // Add back the original quantity
          original_quantity: {{ $originalQuantity }},
          quantity: {{ $item->quantity }}
        });
      @endforeach
      
      // If no items exist, add one empty item
      if (this.items.length === 0) {
        this.addItem();
      }
    }
  }
}
</script>
@endsection