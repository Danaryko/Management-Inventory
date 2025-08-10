<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'App')</title>

  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
  <div x-data="{ sidebarOpen:false }" class="min-h-screen flex">

    {{-- Sidebar (partial) --}}
    @include('layouts.sidebar')

    {{-- Overlay mobile --}}
    <div class="fixed inset-0 bg-black/40 z-30 md:hidden" x-show="sidebarOpen" @click="sidebarOpen=false" x-cloak></div>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-h-screen md:ml-0">
      {{-- Navbar (partial) --}}
      @include('layouts.navbar')

      {{-- Flash & errors --}}
      <main class="mx-auto w-full max-w-7xl flex-1 p-4">
        @if (session('status'))
          <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-2">{{ session('status') }}</div>
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

      {{-- Footer (partial) --}}
      @include('layouts.footer')
    </div>
  </div>

  {{-- AlpineJS untuk toggle sidebar --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
