<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - Management Inventory</title>
  
  {{-- Tailwind CSS CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  
  {{-- Custom Tailwind Config --}}
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
  
  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  {{-- Enhanced Dashboard Styles --}}
  <link href="{{ asset('css/enhanced-dashboard.css') }}" rel="stylesheet">
  
  {{-- Additional styles for specific pages --}}
  @stack('styles')
</head>
<body class="h-full font-sans antialiased">
  <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-50">

    {{-- Sidebar (partial) --}}
    @include('layouts.sidebar')

    {{-- Mobile overlay --}}
    <div 
      class="fixed inset-0 bg-black/40 z-30 md:hidden" 
      x-show="sidebarOpen" 
      @click="sidebarOpen=false" 
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      x-cloak
    ></div>

    {{-- Main content area --}}
    <div class="flex-1 flex flex-col min-h-screen md:ml-0">
      {{-- Navbar (partial) --}}
      @include('layouts.navbar')

      {{-- Flash messages & errors --}}
      <main class="mx-auto w-full max-w-7xl flex-1 p-4 sm:p-6 lg:p-8">
        {{-- Success messages --}}
        @if (session('status'))
          <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
              </div>
              <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                  <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                          class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Error messages --}}
        @if ($errors->any())
          <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                <div class="mt-2 text-sm text-red-700">
                  <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                  <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                          class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Page content --}}
        @yield('content')
      </main>

      {{-- Footer (partial) --}}
      @include('layouts.footer')
    </div>
  </div>

  {{-- AlpineJS for interactive components --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  
  {{-- Enhanced Dashboard JavaScript --}}
  <script src="{{ asset('js/enhanced-dashboard.js') }}"></script>
  
  {{-- Page-specific scripts --}}
  @stack('scripts')
</body>
</html>
