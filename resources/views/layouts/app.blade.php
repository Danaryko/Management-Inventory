<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'App')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
  <div x-data="{ sidebarOpen:false }" class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 px-3 py-4 md:static md:translate-x-0"
           :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}"
           x-transition x-cloak>
      <div class="flex items-center justify-between mb-6">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold">My App</a>
        <button class="md:hidden p-2 rounded hover:bg-gray-100" @click="sidebarOpen=false" aria-label="Tutup sidebar">
          ✕
        </button>
      </div>

      <nav class="space-y-1">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg
           {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <span>Dashboard</span>
        </a>

        <a href="{{ route('profile') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg
           {{ request()->routeIs('profile') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
          <span>Profil</span>
        </a>

        @auth
          @if(auth()->user()->role === 'admin')
          <a href="{{ route('users.index') }}"
             class="flex items-center gap-2 px-3 py-2 rounded-lg
             {{ request()->routeIs('users.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <span>User Management</span>
          </a>
          @endif
        @endauth
      </nav>
    </aside>

    {{-- Overlay mobile --}}
    <div class="fixed inset-0 bg-black/40 z-30 md:hidden" x-show="sidebarOpen" @click="sidebarOpen=false" x-cloak></div>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-h-screen md:ml-0">
      {{-- Navbar --}}
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

      {{-- Flash & errors --}}
      <main class="mx-auto w-full max-w-7xl flex-1 p-4">
        @if (session('status'))
          <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-2">
            {{ session('status') }}
          </div>
        @endif
        @if ($errors->any())
          <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3">
            <div class="font-semibold mb-1">Terjadi kesalahan:</div>
            <ul class="list-disc list-inside text-sm">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @yield('content')
      </main>

      {{-- Footer --}}
      <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-4 text-sm text-gray-500">
          © {{ date('Y') }} - Your Company
        </div>
      </footer>
    </div>
  </div>

  {{-- AlpineJS untuk toggle (opsional) --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
