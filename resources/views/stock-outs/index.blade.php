@extends('layouts.app')

@section('title', 'Stock Out')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Stock Out Transactions</h1>
        <p class="text-gray-600">Manage outgoing stock transactions</p>
      </div>
      <a href="{{ route('stock-outs.create') }}" 
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        New Stock Out
      </a>
    </div>
  </div>

  {{-- Filters --}}
  <div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" action="{{ route('stock-outs.index') }}" class="grid md:grid-cols-4 gap-4">
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
        <input type="text" 
               id="search"
               name="search" 
               value="{{ request('search') }}"
               placeholder="Reference number..." 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
      </div>

      <div>
        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
        <input type="date" 
               name="date_from" 
               id="date_from"
               value="{{ request('date_from') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
      </div>

      <div>
        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
        <input type="date" 
               name="date_to" 
               id="date_to"
               value="{{ request('date_to') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
      </div>

      <div class="flex items-end gap-2">
        <button type="submit" 
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Filter
        </button>
        @if(request()->hasAny(['search', 'date_from', 'date_to']))
          <a href="{{ route('stock-outs.index') }}" 
             class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Clear
          </a>
        @endif
      </div>
    </form>
  </div>

  {{-- Stock Out Table --}}
  <div class="bg-white rounded-lg shadow">
    @if(isset($stockOuts) && $stockOuts->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">By</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($stockOuts as $stockOut)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $stockOut->reference_number }}</div>
                  @if($stockOut->notes)
                    <div class="text-sm text-gray-500">{{ Str::limit($stockOut->notes, 30) }}</div>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $stockOut->date->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $stockOut->items->count() }} items
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  ${{ number_format($stockOut->total_amount, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $stockOut->user->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('stock-outs.show', $stockOut) }}" 
                       class="text-gray-600 hover:text-gray-900">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </a>
                    <a href="{{ route('stock-outs.edit', $stockOut) }}" 
                       class="text-blue-600 hover:text-blue-900">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($stockOuts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $stockOuts->appends(request()->query())->links() }}
        </div>
      @endif
    @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No stock out transactions</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new stock out transaction.</p>
        <div class="mt-6">
          <a href="{{ route('stock-outs.create') }}" 
             class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Stock Out
          </a>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection