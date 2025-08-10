@extends('layouts.app')

@section('title', 'Supplier Details')
@section('page_title', 'Supplier')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
  {{-- Header --}}
  <div class="mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('suppliers.index') }}" 
         class="text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h1>
        <p class="text-gray-600">Supplier details and transaction history</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('suppliers.edit', $supplier) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Edit
        </a>
      </div>
    </div>
  </div>

  {{-- Supplier Information --}}
  <div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
      <dl class="space-y-4">
        <div>
          <dt class="text-sm font-medium text-gray-500">Company Name</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $supplier->name }}</dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $supplier->contact_person ?: 'Not specified' }}</dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Address</dt>
          <dd class="mt-1 text-sm text-gray-900">{{ $supplier->address ?: 'No address provided' }}</dd>
        </div>
      </dl>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
      <dl class="space-y-4">
        <div>
          <dt class="text-sm font-medium text-gray-500">Email</dt>
          <dd class="mt-1 text-sm text-gray-900">
            @if($supplier->email)
              <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-800">{{ $supplier->email }}</a>
            @else
              Not provided
            @endif
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Phone</dt>
          <dd class="mt-1 text-sm text-gray-900">
            @if($supplier->phone)
              <a href="tel:{{ $supplier->phone }}" class="text-blue-600 hover:text-blue-800">{{ $supplier->phone }}</a>
            @else
              Not provided
            @endif
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Total Transactions</dt>
          <dd class="mt-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
              {{ $supplier->stockIns->count() }} transactions
            </span>
          </dd>
        </div>
      </dl>
    </div>
  </div>

  {{-- Recent Transactions --}}
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
      <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
    </div>
    
    @if($supplier->stockIns->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($supplier->stockIns as $stockIn)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $stockIn->transaction_date->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  @if($stockIn->stockInItems)
                    @foreach($stockIn->stockInItems->take(3) as $item)
                      <div>{{ $item->product->name }} ({{ $item->quantity }} units)</div>
                    @endforeach
                    @if($stockIn->stockInItems->count() > 3)
                      <div class="text-gray-500">... and {{ $stockIn->stockInItems->count() - 3 }} more</div>
                    @endif
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ${{ number_format($stockIn->total_amount ?? 0, 2) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ $stockIn->notes ?: 'No notes' }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
        <p class="mt-1 text-sm text-gray-500">No stock in transactions found for this supplier.</p>
      </div>
    @endif
  </div>
</div>
@endsection
