<header class="sticky top-0 z-20 bg-white border-b border-gray-200">
  <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <button class="md:hidden p-2 rounded hover:bg-gray-100" @click="sidebarOpen=true" aria-label="Buka sidebar">☰</button>
      <h1 class="text-lg font-semibold">@yield('page_title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center gap-3">
      @auth
        <span class="hidden sm:inline text-sm text-gray-600">Halo, {{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:opacity-90">Logout</button>
        </form>
      @endauth
      @guest
        <a class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:opacity-90" href="{{ route('login') }}">Login</a>
      @endguest
    </div>
  </div>
</header>
