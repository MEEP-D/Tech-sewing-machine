<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="scroll-smooth"
      x-data
      :class="{ 'dark': $store.theme.darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('seo.meta', ['seo' => $seo ?? []])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                darkMode: localStorage.getItem('darkMode') === 'true',
                toggle() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                }
            })
        })
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-white text-zinc-900 selection:bg-zinc-900 selection:text-white transition-colors duration-500 dark:bg-zinc-950 dark:text-zinc-100">
    
    <div class="flex flex-col min-h-screen">
        {{-- Navigation --}}
        @include('partials.navbar')

        {{-- Main Content --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('partials.footer')
    </div>

    {{-- Mobile Menu Overlay (handled by Navbar component via Alpine) --}}
    
    @stack('scripts')
</body>
</html>
