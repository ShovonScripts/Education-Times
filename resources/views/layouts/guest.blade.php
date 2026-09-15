<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#f5f5f5] text-[#111] font-sans antialiased min-h-screen flex flex-col">
    <header class="bg-white border-b border-[#e5e5e5] shrink-0">
        <div class="max-w-[1240px] mx-auto px-4 h-14 flex items-center justify-between">
            <a href="/" class="flex items-center">
                @if($siteLogo = \App\Models\Setting::get('site_logo'))
                    <img src="{{ Storage::url($siteLogo) }}" alt="{{ \App\Models\Setting::get('site_name_bn', config('app.name')) }}" class="h-8 w-auto">
                @else
                    <span class="font-serif font-black text-xl text-[#111]">{{ \App\Models\Setting::get('site_name_bn', 'Education Times') }}</span>
                @endif
            </a>
            <a href="/" class="text-sm font-semibold text-[#111] hover:text-[#E02020] transition">হোমপেজ</a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-10">
        @yield('content')
    </main>

    <footer class="bg-[#111] text-white shrink-0">
        <div class="max-w-[1240px] mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-white/60">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name_bn', 'Education Times') }}. সর্বস্বত্ব সংরক্ষিত।</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('pages.privacy') }}" class="text-xs text-white/60 hover:text-white transition">প্রাইভেসি</a>
                <a href="{{ route('pages.terms') }}" class="text-xs text-white/60 hover:text-white transition">শর্তাবলী</a>
                <a href="{{ route('contact.index') }}" class="text-xs text-white/60 hover:text-white transition">যোগাযোগ</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
