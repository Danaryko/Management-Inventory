@extends('layouts.app')

@section('title', 'Stock In Details')
@section('page_title', content: 'Stock In')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex justify-between items-start">
      <div class="flex items-center gap-4">
        <a href="{{ route('stock-ins.index') }}" 
           class="text-gray-600 hover:text-gray-900">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </a>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Stock In Details</h1>
          <p class="text-gray-600">{{ $stockIn->reference_number }}</p>
        </div>
      </div>
      
      <div class="flex gap-2">
        <a href="{{ route('stock-ins.edit', $stockIn) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Edit
        </a>
        
        <form action="{{ route('stock-ins.destroy', $stockIn) }}" 
              method="POST" 
              class="inline"
              onsubmit="return confirm('Are you sure you want to delete this stock in transaction? This action cannot be undone.')">
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
          <p class="mt-1 text-sm text-gray-900">{{ $stockIn->reference_number }}</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Date</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockIn->date->format('F j, Y') }}</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Created By</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockIn->user->name }}</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Created At</label>
          <p class="mt-1 text-sm text-gray-900">{{ $stockIn->created_at->format('F j, Y g:i A') }}</p>
        </div>
        
        @if($stockIn->updated_at != $stockIn->created_at)
          <div>
            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
            <p class="mt-1 text-sm text-gray-900">{{ $stockIn->updated_at->format('F j, Y g:i A') }}</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Supplier Information --}}
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Supplier Information</h3>
      
      @if($stockIn->supplier)
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Supplier Name</label>
            <p class="mt-1 text-sm text-gray-900">{{ $stockIn->supplier->name }}</p>
          </div>
          
          @if($stockIn->supplier->contact_person)
            <div>
              <label class="block text-sm font-medium text-gray-700">Contact Person</label>
              <p class="mt-1 text-sm text-gray-900">{{ $stockIn->supplier->contact_person }}</p>
            </div>
          @endif
          
          @if($stockIn->supplier->email)
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <p class="mt-1 text-sm text-gray-900">
                <a href="mailto:{{ $stockIn->supplier->email }}" class="text-blue-600 hover:text-blue-800">
                  {{ $stockIn->supplier->email }}
                </a>
              </p>
            </div>
          @endif
          
          @if($stockIn->supplier->phone)
            <div>
              <label class="block text-sm font-medium text-gray-700">Phone</label>
              <p class="mt-1 text-sm text-gray-900">
                <a href="tel:{{ $stockIn->supplier->phone }}" class="text-blue-600 hover:text-blue-800">
                  {{ $stockIn->supplier->phone }}
                </a>
              </p>
            </div>
          @endif
        </div>
      @else
        <p class="text-sm text-gray-500 italic">No supplier specified for this transaction.</p>
      @endif
    </div>
  </div>

  {{-- Notes --}}
  @if($stockIn->notes)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
      <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $stockIn->notes }}</p>
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
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($stockIn->items as $item)
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
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ number_format($item->quantity) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ number_format($item->purchase_price, 2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${{ number_format($item->subtotal, 2) }}
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
          <tr>
            <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
              Total Amount:
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600">
              ${{ number_format($stockIn->total_amount, 2) }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Summary Statistics --}}
  <div class="grid md:grid-cols-3 gap-4 mt-6">
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
        </div>
        <div class="ml-4">
          <div class="text-sm font-medium text-green-900">Total Items</div>
          <div class="text-2xl font-bold text-green-600">{{ $stockIn->items->count() }}</div>
        </div>
      </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
          </svg>
        </div>
        <div class="ml-4">
          <div class="text-sm font-medium text-blue-900">Total Quantity</div>
          <div class="text-2xl font-bold text-blue-600">{{ number_format($stockIn->items->sum('quantity')) }}</div>
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
          <div class="text-sm font-medium text-purple-900">Average Price</div>
          <div class="text-2xl font-bold text-purple-600">
            @if($stockIn->items->sum('quantity') > 0)
              ${{ number_format($stockIn->total_amount / $stockIn->items->sum('quantity'), 2) }}
            @else
              $0.00
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection