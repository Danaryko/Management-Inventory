@extends('layouts.app')

@section('title', 'Create Stock In')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('stock-ins.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Stock In</h1>
        <p class="text-gray-600">Add new incoming stock transaction</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="bg-white rounded-lg shadow">
    <form action="{{ route('stock-ins.store') }}" method="POST" class="p-6 space-y-6" x-data="stockInForm()">
      @csrf

      {{-- Basic Information --}}
      <div class="grid md:grid-cols-3 gap-6">
        {{-- Reference Number --}}
        <div>
          <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
            Reference Number *
          </label>
          <input type="text" 
                 id="reference_number" 
                 name="reference_number" 
                 value="{{ old('reference_number', $referenceNumber) }}"
                 required
                 readonly
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 @error('reference_number') border-red-500 @enderror">
          @error('reference_number')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Supplier --}}
        <div>
          <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
            Supplier
          </label>
          <select name="supplier_id" 
                  id="supplier_id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('supplier_id') border-red-500 @enderror">
            <option value="">Select Supplier (Optional)</option>
            @foreach($suppliers as $supplier)
              <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
              </option>
            @endforeach
          </select>
          @error('supplier_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Date --}}
        <div>
          <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
            Stock In Date *
          </label>
          <input type="date" 
                 id="date" 
                 name="date" 
                 value="{{ old('date', date('Y-m-d')) }}"
                 required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('date') border-red-500 @enderror">
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
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('notes') border-red-500 @enderror"
                  placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
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
                  class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
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
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Price</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                      <option value="">Select Product</option>
                      @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-name="{{ $product->name }}"
                                data-sku="{{ $product->sku }}">
                          {{ $product->name }} ({{ $product->sku }})
                        </option>
                      @endforeach
                    </select>
                    <span x-show="item.product_name" 
                          x-text="item.product_name" 
                          class="text-sm text-gray-500 mt-1 block"></span>
                  </td>
                  <td class="px-4 py-3">
                    <input type="number" 
                           :name="`items[${index}][quantity]`"
                           x-model.number="item.quantity"
                           @input="calculateSubtotal(index)"
                           min="1"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Qty">
                  </td>
                  <td class="px-4 py-3">
                    <input type="number" 
                           :name="`items[${index}][purchase_price]`"
                           x-model.number="item.purchase_price"
                           @input="calculateSubtotal(index)"
                           min="0"
                           step="0.01"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="0.00">
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
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                  No items added yet. Click "Add Item" to get started.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {{-- Total --}}
        <div class="mt-4 flex justify-end">
          <div class="bg-gray-50 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-4">
              <span class="text-sm font-medium text-gray-700">Total Amount:</span>
              <span class="text-lg font-bold text-green-600" x-text="formatCurrency(totalAmount)"></span>
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
        <a href="{{ route('stock-ins.index') }}" 
           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Cancel
        </a>
        <button type="submit" 
                :disabled="items.length === 0"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg">
          Create Stock In
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function stockInForm() {
  return {
    items: [],
    
    addItem() {
      this.items.push({
        product_id: '',
        product_name: '',
        quantity: 1,
        purchase_price: 0,
        subtotal: 0
      });
    },
    
    removeItem(index) {
      this.items.splice(index, 1);
    },
    
    updateProductInfo(index) {
      const select = document.querySelector(`select[name="items[${index}][product_id]"]`);
      const selectedOption = select.options[select.selectedIndex];
      
      if (selectedOption.value) {
        this.items[index].product_name = selectedOption.dataset.name;
      } else {
        this.items[index].product_name = '';
      }
      
      this.calculateSubtotal(index);
    },
    
    calculateSubtotal(index) {
      const item = this.items[index];
      item.subtotal = (item.quantity || 0) * (item.purchase_price || 0);
    },
    
    get totalAmount() {
      return this.items.reduce((total, item) => total + (item.subtotal || 0), 0);
    },
    
    formatCurrency(amount) {
      return '$' + (amount || 0).toFixed(2);
    },
    
    init() {
      // Add initial item
      this.addItem();
    }
  }
}
</script>
@endsection