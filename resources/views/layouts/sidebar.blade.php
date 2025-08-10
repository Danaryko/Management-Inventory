<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 px-3 py-4
              md:static md:translate-x-0"
       :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}"
       x-transition x-cloak>
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('dashboard') }}" class="text-lg font-semibold">My App</a>
    <button class="md:hidden p-2 rounded hover:bg-gray-100" @click="sidebarOpen=false" aria-label="Tutup sidebar">✕</button>
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
