<!-- Sidebar -->
<div class="lg:w-64 lg:flex lg:flex-col lg:fixed lg:inset-y-0">
    <!-- Mobile sidebar -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg lg:hidden">
        @include('layouts.sidebar-content')
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0">
        <div class="flex flex-col flex-grow bg-white border-r border-gray-200 overflow-y-auto">
            @include('layouts.sidebar-content')
        </div>
    </div>
</div>

<!-- Spacer for fixed sidebar on desktop -->
<div class="hidden lg:block lg:w-64 lg:flex-shrink-0"></div>