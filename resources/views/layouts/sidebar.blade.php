<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 px-3 py-4 shadow-lg
              md:static md:translate-x-0 md:shadow-none"
       :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       x-cloak>
       
  {{-- Header --}}
  <div class="flex items-center justify-between mb-8">
    <div class="flex items-center space-x-3">
      <div class="h-8 w-8 bg-gray-900 rounded-lg flex items-center justify-center">
        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
      </div>
      <div>
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-900 hover:text-gray-700">
          Inventory
        </a>
        <p class="text-xs text-gray-500">Management System</p>
      </div>
    </div>
    
    {{-- Close button for mobile --}}
    <button 
      class="md:hidden p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-900" 
      @click="sidebarOpen=false" 
      aria-label="Tutup sidebar"
    >
      <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>

  {{-- Navigation --}}
  <nav class="space-y-2">
    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
       {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
      <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
      </svg>
      <span>Dashboard</span>
    </a>

    {{-- Profile --}}
    <a href="{{ route('profile') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
       {{ request()->routeIs('profile') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
      <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
      </svg>
      <span>Profil</span>
    </a>

    @auth
      @if(auth()->user()->roles === 'admin')
        {{-- Divider --}}
        <div class="pt-4 pb-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">
            Admin
          </p>
        </div>
        
        {{-- User Management --}}
        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('users.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
          <span>User Management</span>
          @if(request()->routeIs('users.*'))
            <div class="ml-auto">
              <div class="h-2 w-2 bg-white rounded-full"></div>
            </div>
          @endif
        </a>

        {{-- Inventory Management (placeholder) --}}
        <a href="#"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span>Inventory</span>
          <span class="ml-auto text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
            Soon
          </span>
        </a>

        {{-- Reports (placeholder) --}}
        <a href="#"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
          <span>Reports</span>
          <span class="ml-auto text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
            Soon
          </span>
        </a>
      @endif
    @endauth
</aside>
