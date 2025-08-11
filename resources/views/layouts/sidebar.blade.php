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
      {{-- Activity History for admins --}}
      @if(in_array(auth()->user()->roles, ['admin']))
        {{-- Divider --}}
        <div class="pt-4 pb-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">
            Activity Management
          </p>
        </div>
        
        {{-- Activity History --}}
        <a href="{{ route('activities.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('activities.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Activity History</span>
          @if(request()->routeIs('activities.*'))
            <div class="ml-auto">
              <div class="h-2 w-2 bg-white rounded-full"></div>
            </div>
          @endif
        </a>
      @endif

      @if(auth()->user()->roles === 'admin')
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
      @endif

      {{-- Inventory Management for operator --}}
      @if(in_array(auth()->user()->roles, ['operator']))
        {{-- Divider --}}
        <div class="pt-4 pb-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">
            Inventory Management
          </p>
        </div>

        {{-- Categories --}}
        <a href="{{ route('categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('categories.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <span>Categories</span>
        </a>

        {{-- Products --}}
        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('products.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span>Products</span>
        </a>

        {{-- Suppliers --}}
        <a href="{{ route('suppliers.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('suppliers.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          <span>Suppliers</span>
        </a>

        {{-- Stock In --}}
        <a href="{{ route('stock-ins.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('stock-ins.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
          </svg>
          <span>Stock In</span>
        </a>

        {{-- Stock Out --}}
        <a href="{{ route('stock-outs.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('stock-outs.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
          <span>Stock Out</span>
        </a>
      @endif

      @if(auth()->user()->roles === 'owner' || auth()->user()->roles === 'admin')
      {{-- Divider --}}
        <div class="pt-4 pb-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">
            Inventory Management
          </p>
        </div>
        {{-- Products --}}
        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('products.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span>Products</span>
        </a>
      @endif

      {{-- Role-specific features --}}
      @if(auth()->user()->roles === 'owner')
        {{-- Reports Section for Owner --}}
        <div class="pt-4 pb-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">
            Reports
          </p>
        </div>

        {{-- Stock Reports --}}
        <a href="{{ route('reports.stock-in') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('reports.stock-in') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Stock In Reports</span>
        </a>

        <a href="{{ route('reports.stock-out') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('reports.stock-out') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Stock Out Reports</span>
        </a>
      @endif

      @if(auth()->user()->roles === 'operator')
        {{-- History Section for Operator --}}
        <div class="pt-4 pb-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">
            History
          </p>
        </div>

        {{-- Stock History --}}
        <a href="{{ route('history.stock-in') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('history.stock-in') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>Stock In History</span>
        </a>

        <a href="{{ route('history.stock-out') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('history.stock-out') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>Stock Out History</span>
        </a>
      @endif
    @endauth
</aside>
