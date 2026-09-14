<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#111111">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    @hasSection('meta_description')
    <meta name="description" content="@yield('meta_description')">
    @endif
    @hasSection('canonical')
    <link rel="canonical" href="@yield('canonical')">
    @endif
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('site_tagline', ''))">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @hasSection('og_image')
    <meta property="og:image" content="@yield('og_image')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    @endif
    @hasSection('structured_data')
    @yield('structured_data')
    @endif
    @stack('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-[#111] font-sans antialiased min-h-screen flex flex-col pb-14 md:pb-0">
    <div id="reading-progress" aria-hidden="true"></div>
    @include('partials.ads.popup')
    @include('partials.header')
    <main class="flex-1 w-full">
        @yield('content')
    </main>
    @include('partials.footer')
    @include('partials.bottom-nav')

    <button id="scrollTop" class="fixed bottom-20 md:bottom-6 right-6 z-40 w-10 h-10 bg-[#111] hover:bg-black text-white shadow-md transition flex items-center justify-center opacity-0 pointer-events-none" aria-label="Back to top">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
    </button>
    <script>
    (function() {
        var btn = document.getElementById('scrollTop');
        if (btn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 400) {
                    btn.classList.remove('opacity-0', 'pointer-events-none');
                    btn.classList.add('opacity-100');
                } else {
                    btn.classList.add('opacity-0', 'pointer-events-none');
                    btn.classList.remove('opacity-100');
                }
            });
            btn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>
