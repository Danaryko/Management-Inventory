@extends('layouts.app')

@section('title', 'Stock Out Details')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex justify-between items-start">
      <div class="flex items-center gap-4">
        <a href="{{ route('stock-outs.index') }}" 
           class="text-gray-600 hover:text-gray-900">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </a>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Stock Out Details</h1>
          <p class="text-gray-600">{{ $stockOut->reference_number }}</p>
        </div>
      </div>
      
      <div class="flex gap-2">
        <a href="{{ route('stock-outs.edit', $stockOut) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Edit
        </a>
        
        <form action="{{ route('stock-outs.destroy', $stockOut) }}" 
              method="POST" 
              class="inline"
              onsubmit="return confirm('Are you sure you want to delete this stock out transaction? This action cannot be undone.')">
          @csrf
          @method('DELETE')
          <button type="submit" 
                  class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Transaction Information --}}
  <div class="grid md:grid-cols-2 gap-6 mb-6">
    {{-- Basic Information --}}
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Information</h3>
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Reference Number</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockOut->reference_number }}</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Date</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockOut->date->format('F j, Y') }}</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Created By</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockOut->user->name }}</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Created At</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockOut->created_at->format('F j, Y g:i A') }}</p>
        </div>
        
        @if($stockOut->updated_at != $stockOut->created_at)
          <div>
            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
            <p class="mt-1 text-sm text-gray-900">{{ $stockOut->updated_at->format('F j, Y g:i A') }}</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Transaction Summary --}}
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Summary</h3>
      
      <div class="space-y-4">
        <div class="flex justify-between items-center py-2 border-b">
          <span class="text-sm font-medium text-gray-700">Total Items</span>
          <span class="text-sm text-gray-900">{{ $stockOut->items->count() }}</span>
        </div>
        
        <div class="flex justify-between items-center py-2 border-b">
          <span class="text-sm font-medium text-gray-700">Total Quantity</span>
          <span class="text-sm text-gray-900">{{ number_format($stockOut->items->sum('quantity')) }}</span>
        </div>
        
        <div class="flex justify-between items-center py-2 border-b">
          <span class="text-sm font-medium text-gray-700">Average Price</span>
          <span class="text-sm text-gray-900">
            @if($stockOut->items->sum('quantity') > 0)
              ${{ number_format($stockOut->total_amount / $stockOut->items->sum('quantity'), 2) }}
            @else
              $0.00
            @endif
          </span>
        </div>
        
        <div class="flex justify-between items-center py-2 text-lg font-semibold">
          <span class="text-gray-900">Total Amount</span>
          <span class="text-red-600">${{ number_format($stockOut->total_amount, 2) }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Notes --}}
  @if($stockOut->notes)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
      <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $stockOut->notes }}</p>
    </div>
  @endif

  {{-- Items --}}
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-medium text-gray-900">Items</h3>
    </div>
    
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Out</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($stockOut->items as $item)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                    @if($item->product->brand)
                      <div class="text-sm text-gray-500">{{ $item->product->brand }}</div>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                  {{ $item->product->sku }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                  {{ $item->product->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                  {{ number_format($item->product->stock_quantity) }}
                </span>
                @if($item->product->isLowStock())
                  <div class="text-xs text-orange-600 mt-1">Low Stock</div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ number_format($item->quantity) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ number_format($item->sale_price, 2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${{ number_format($item->subtotal, 2) }}
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
          <tr>
            <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
              Total Amount:
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-red-600">
              ${{ number_format($stockOut->total_amount, 2) }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Summary Statistics --}}
  <div class="grid md:grid-cols-3 gap-4 mt-6">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
        </div>
        <div class="ml-4">
          <div class="text-sm font-medium text-red-900">Total Items</div>
          <div class="text-2xl font-bold text-red-600">{{ $stockOut->items->count() }}</div>
        </div>
      </div>
    </div>

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
          </svg>
        </div>
        <div class="ml-4">
          <div class="text-sm font-medium text-orange-900">Total Quantity Out</div>
          <div class="text-2xl font-bold text-orange-600">{{ number_format($stockOut->items->sum('quantity')) }}</div>
        </div>
      </div>
    </div>

    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
        </div>
        <div class="ml-4">
          <div class="text-sm font-medium text-purple-900">Revenue Generated</div>
          <div class="text-2xl font-bold text-purple-600">${{ number_format($stockOut->total_amount, 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Stock Impact Warning --}}
  @php
    $lowStockItems = $stockOut->items->filter(function($item) {
      return $item->product->isLowStock();
    });
  @endphp
  
  @if($lowStockItems->count() > 0)
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-yellow-800">Stock Level Warning</h3>
          <div class="mt-2 text-sm text-yellow-700">
            <p>The following products are now at or below their minimum stock level:</p>
            <ul class="list-disc list-inside mt-1">
              @foreach($lowStockItems as $item)
                <li>{{ $item->product->name }} (Current: {{ $item->product->stock_quantity }}, Min: {{ $item->product->min_stock_level }})</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection