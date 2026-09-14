<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'ET Admin')</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('editor')
    <script>var m=localStorage.getItem('etTheme');if(m==='dark'||(!m&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>
<body class="bg-[#f5f5f5] dark:bg-[#121212] text-[#1a1a1a] dark:text-[#e0e0e0] font-sans antialiased min-h-screen">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white dark:bg-[#1e1e1e] border-b border-[#e0e0e0] dark:border-[#333] h-14 flex items-center px-6 sticky top-0 z-40">
                <button type="button" onclick="document.getElementById('adminSidebar').classList.toggle('hidden');document.getElementById('adminSidebar').classList.toggle('flex')" class="md:hidden mr-3 text-[#666] dark:text-[#999]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-3 w-full">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
                        @if($siteLogo = \App\Models\Setting::get('site_logo'))
                            <img src="{{ Storage::url($siteLogo) }}" alt="{{ \App\Models\Setting::get('site_name_bn', config('app.name')) }}" class="h-7 w-auto">
                        @else
                            <span class="font-serif font-bold text-lg dark:text-white">ET <span class="text-[#E02020] dark:text-[#ff6b6b]">Admin</span></span>
                        @endif
                    </a>
                    <span class="text-[#ccc] dark:text-[#444] hidden sm:inline">|</span>
                    <a href="/" target="_blank" class="border border-[#e0e0e0] dark:border-[#444] text-xs text-[#666] dark:text-[#aaa] px-3 py-1.5 hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] hover:text-[#0d0d0d] dark:hover:text-white transition hidden sm:flex items-center gap-1.5 shrink-0">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View Site
                    </a>
                    <div class="ml-auto flex items-center gap-3">
                        <button type="button" id="darkModeToggleAdmin" class="text-[#666] dark:text-[#999] hover:text-[#0d0d0d] dark:hover:text-white transition p-1" title="Dark Mode">
                            <svg class="h-5 w-5" id="darkModeIconAdmin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>
                        <span class="text-xs text-[#666] dark:text-[#999] hidden md:block">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-[#999] dark:text-[#777] hover:text-[#E02020] dark:hover:text-[#ff6b6b] transition flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="alert-success flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-error flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert-error">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script>
    (function() {
        var toggle = document.getElementById('darkModeToggleAdmin');
        var icon = document.getElementById('darkModeIconAdmin');
        var isDark = document.documentElement.classList.contains('dark');

        function updateIconAdmin() {
            if (document.documentElement.classList.contains('dark')) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
            }
        }
        updateIconAdmin();
        if (toggle) {
            toggle.addEventListener('click', function() {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('etTheme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                updateIconAdmin();
            });
        }
    })();
    </script>
</body>
</html>
