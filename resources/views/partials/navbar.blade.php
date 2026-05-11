<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white/80 backdrop-blur-md border-b border-zinc-200 dark:bg-zinc-950/80 dark:border-zinc-800': scrolled, 'bg-transparent border-transparent': !scrolled }"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="group flex items-center gap-2">
                    <div class="w-10 h-10 bg-zinc-900 rounded-lg flex items-center justify-center text-white font-bold group-hover:scale-110 transition-transform">
                        T
                    </div>
                    <span class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">TechSewing</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">Trang chủ</a>
                
                {{-- Dynamic Product Menu --}}
                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center gap-1 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        Sản phẩm
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 w-64 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-2xl py-2 mt-2">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white">Tất cả sản phẩm</a>
                        <div class="h-px bg-zinc-100 dark:bg-zinc-800 my-1"></div>
                        @foreach(App\Models\Category::where('type', 'product')->whereNull('parent_id')->take(5)->get() as $cat)
                            <a href="{{ route('products.category', $cat->slug) }}" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('news.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">Tin tức</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">Giới thiệu</a>
                
                {{-- Theme Toggle --}}
                <button @click="$store.theme.toggle()" class="p-2 text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                    <svg x-show="!$store.theme.darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="$store.theme.darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-zinc-900 rounded-full hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-all active:scale-95 shadow-lg shadow-zinc-900/10">
                    Liên hệ ngay
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-zinc-600 dark:text-zinc-400 p-2">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden bg-white dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800">
        <div class="px-4 pt-2 pb-6 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-4 text-base font-medium text-zinc-900 dark:text-white border-b border-zinc-50 dark:border-zinc-900">Trang chủ</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-4 text-base font-medium text-zinc-900 dark:text-white border-b border-zinc-50 dark:border-zinc-900">Sản phẩm</a>
            <a href="{{ route('news.index') }}" class="block px-3 py-4 text-base font-medium text-zinc-900 dark:text-white border-b border-zinc-50 dark:border-zinc-900">Tin tức</a>
            <a href="{{ route('about') }}" class="block px-3 py-4 text-base font-medium text-zinc-900 dark:text-white border-b border-zinc-50 dark:border-zinc-900">Giới thiệu</a>
            <div class="pt-6 flex items-center justify-between gap-4">
                <a href="{{ route('contact') }}" class="flex-grow text-center py-4 bg-zinc-900 text-white rounded-xl font-bold dark:bg-white dark:text-zinc-900">Liên hệ ngay</a>
                <button @click="$store.theme.toggle()" class="p-4 bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 rounded-xl">
                    <svg x-show="!$store.theme.darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="$store.theme.darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>
            </div>
        </div>
    </div>
</nav>
