<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">
    @auth
        <!-- Layout untuk user yang sudah login -->
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col">
                <!-- Navbar -->
                @include('layouts.navbar')

                <!-- Main Content -->
                <main class="flex-1 p-6">
                    <div class="max-w-7xl mx-auto">
                        <!-- Page Header -->
                        @if(View::hasSection('header'))
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-gray-900">
                                    @yield('header')
                                </h1>
                            </div>
                        @endif

                        <!-- Page Content -->
                        @yield('content')
                    </div>
                </main>

                <!-- Footer -->
                @include('layouts.footer')
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-gray-600 bg-opacity-50 lg:hidden"
             @click="sidebarOpen = false">
        </div>
    @else
        <!-- Layout untuk guest -->
        <div class="min-h-screen flex flex-col">
            <!-- Header untuk Guest -->
            <header class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path>
                                </svg>
                            </div>
                            <h1 class="text-xl font-semibold text-gray-900">
                                @yield('header', 'Aplikasi Management System')
                            </h1>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full space-y-8">
                    @yield('content')
                </div>
            </main>

            <!-- Footer untuk Guest -->
            <footer class="bg-white border-t">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <p class="text-center text-sm text-gray-500">
                        © {{ date('Y') }} Aplikasi Management System. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    @endauth
</body>
</html>