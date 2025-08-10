<header class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
  <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
      {{-- Mobile menu button --}}
      <button 
        class="md:hidden p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-900 transition-colors" 
        @click="sidebarOpen=true" 
        aria-label="Buka sidebar"
      >
        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
      
      {{-- Page title --}}
      <div>
        <h1 class="text-lg font-semibold text-gray-900">@yield('page_title', 'Dashboard')</h1>
        <p class="text-xs text-gray-500 hidden sm:block">Management Inventory System</p>
      </div>
    </div>

    {{-- Right side content --}}
    <div class="flex items-center gap-3">
      @auth
        {{-- User info (hidden on small screens) --}}
        <div class="hidden lg:flex items-center gap-3">
          <div class="text-right">
            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">
              <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                {{ auth()->user()->role === 'admin' ? 'bg-red-100 text-red-800' : 
                   (auth()->user()->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                {{ ucfirst(auth()->user()->role) }}
              </span>
            </p>
          </div>
          
          {{-- Avatar --}}
          <div class="h-8 w-8 rounded-full bg-gray-900 flex items-center justify-center">
            <span class="text-white font-medium text-sm">
              {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>
          </div>
        </div>

        {{-- Mobile user info --}}
        <div class="lg:hidden flex items-center gap-2">
          <div class="h-8 w-8 rounded-full bg-gray-900 flex items-center justify-center">
            <span class="text-white font-medium text-sm">
              {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>
          </div>
          <span class="text-sm font-medium text-gray-700 hidden sm:inline">
            {{ explode(' ', auth()->user()->name)[0] }}
          </span>
        </div>

        {{-- Logout button --}}
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button 
            type="submit"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors"
            title="Logout"
          >
            <svg class="h-4 w-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="hidden sm:inline">Logout</span>
          </button>
        </form>
      @endauth
      
      @guest
        <div class="flex items-center gap-2">
          <a 
            href="{{ route('register') }}"
            class="hidden sm:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors"
          >
            Daftar
          </a>
          <a 
            href="{{ route('login') }}"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors"
          >
            Masuk
          </a>
        </div>
      @endguest
    </div>
  </div>
</header>
