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