@extends('layouts.app')

@section('title', 'Activity History')
@section('page_title', 'Activity History')

@section('content')
<div class="space-y-6">
  {{-- Header Section --}}
  <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl text-white p-6 sm:p-8">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold mb-2">
          Activity History
        </h2>
        <p class="text-blue-200 text-sm sm:text-base">
          Track and monitor all activities in the system
        </p>
      </div>
      <div class="hidden sm:block">
        <div class="h-16 w-16 bg-white/10 rounded-full flex items-center justify-center">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Filters Section --}}
  <div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-900">Filters & Search</h3>
      <div class="flex items-center space-x-2">
        <button id="auto-refresh-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span id="auto-refresh-text">Auto Refresh</span>
        </button>
        <button onclick="exportActivities('csv')" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Export CSV
        </button>
        <!-- <button onclick="exportActivities('excel')" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
          </svg>
          Export Excel
        </button> -->
      </div>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('activities.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
        <select name="action" id="action" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <option value="">All Actions</option>
          @foreach($actions as $action)
            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
              {{ ucfirst(str_replace('_', ' ', $action)) }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>

      <div>
        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>

      <div class="flex items-end space-x-2">
        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
          <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Filter
        </button>
        <a href="{{ route('activities.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
          <svg class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </a>
      </div>
    </form>
  </div>

  {{-- Activities Table --}}
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
        <div class="flex items-center space-x-2 text-sm text-gray-500">
          <div class="flex items-center">
            <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
            <span>Live Updates</span>
          </div>
          <span>•</span>
          <span>{{ $activities->total() }} total records</span>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Activity
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Description
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              User
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Date & Time
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200" id="activities-table-body">
          @forelse($activities as $activity)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center {{ getActivityColor($activity->action) }}">
                      {!! getActivityIcon($activity->action) !!}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ $activity->model_type ? class_basename($activity->model_type) : 'System' }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ $activity->description }}</div>
                @if($activity->model_id)
                  <div class="text-sm text-gray-500">ID: {{ $activity->model_id }}</div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-xs font-medium text-gray-600">
                      {{ substr($activity->user->name ?? 'System', 0, 2) }}
                    </span>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'System' }}</div>
                    <div class="text-sm text-gray-500">{{ $activity->user->roles ?? 'system' }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div>{{ $activity->created_at->format('M d, Y') }}</div>
                <div class="text-gray-500">{{ $activity->created_at->format('h:i A') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                  Completed
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                  <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">No activities found</h3>
                  <p class="text-gray-500">No activities match your current filters.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($activities->hasPages())
      <div class="px-6 py-4 border-t border-gray-200">
        {{ $activities->appends(request()->query())->links() }}
      </div>
    @endif
  </div>
</div>

{{-- Auto-refresh and Export JavaScript --}}
<script>
let autoRefreshInterval = null;
let isAutoRefreshEnabled = false;

document.addEventListener('DOMContentLoaded', function() {
    const autoRefreshBtn = document.getElementById('auto-refresh-btn');
    const autoRefreshText = document.getElementById('auto-refresh-text');
    
    autoRefreshBtn.addEventListener('click', function() {
        if (isAutoRefreshEnabled) {
            // Disable auto refresh
            clearInterval(autoRefreshInterval);
            isAutoRefreshEnabled = false;
            autoRefreshText.textContent = 'Auto Refresh';
            autoRefreshBtn.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
            autoRefreshBtn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
        } else {
            // Enable auto refresh
            autoRefreshInterval = setInterval(refreshActivities, 30000); // 30 seconds
            isAutoRefreshEnabled = true;
            autoRefreshText.textContent = 'Auto Refresh ON';
            autoRefreshBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
            autoRefreshBtn.classList.add('bg-green-50', 'text-green-700', 'border-green-300');
        }
    });
});

function refreshActivities() {
    const formData = new FormData(document.querySelector('form'));
    const queryString = new URLSearchParams(formData).toString();
    
    fetch(`{{ route('activities.index') }}?${queryString}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.querySelector('#activities-table-body');
        if (newTableBody) {
            document.getElementById('activities-table-body').innerHTML = newTableBody.innerHTML;
        }
    })
    .catch(error => console.error('Auto-refresh failed:', error));
}

function exportActivities(format) {
    const formData = new FormData(document.querySelector('form'));
    formData.append('export', format);
    const queryString = new URLSearchParams(formData).toString();
    
    window.location.href = `{{ route('activities.export') }}?${queryString}`;
}
</script>

@php
function getActivityColor($action) {
    $colors = [
        'created' => 'bg-green-100',
        'updated' => 'bg-blue-100',
        'deleted' => 'bg-red-100',
        'login' => 'bg-purple-100',
        'logout' => 'bg-gray-100',
        'stock_in' => 'bg-emerald-100',
        'stock_out' => 'bg-orange-100',
    ];
    return $colors[$action] ?? 'bg-gray-100';
}

function getActivityIcon($action) {
    $icons = [
        'created' => '<svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
        'updated' => '<svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
        'deleted' => '<svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
        'login' => '<svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>',
        'logout' => '<svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>',
        'stock_in' => '<svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18M13 20v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>',
        'stock_out' => '<svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7M13 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>',
    ];
    return $icons[$action] ?? '<svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
}
@endphp

@endsection