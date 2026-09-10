<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'স্টাফ - ' . config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) { document.documentElement.classList.add('dark'); }</script>
</head>
<body class="bg-[#f5f5f5] dark:bg-[#121212] text-[#1a1a1a] dark:text-[#e0e0e0] font-sans antialiased min-h-screen">
    <div class="flex min-h-screen">
        <aside id="staffSidebar" class="w-56 bg-[#0d0d0d] dark:bg-[#0a0a0a] text-white hidden md:flex flex-col shrink-0">
            <div class="h-14 flex items-center px-5 border-b border-white/10">
                <a href="{{ route('staff.articles.index') }}" class="font-serif font-bold text-base">ET <span class="text-[#E02020]">স্টাফ</span></a>
            </div>
            <nav class="flex-1 py-4 px-3 space-y-1 text-sm overflow-y-auto">
                <a href="{{ route('staff.articles.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded transition @if(request()->routeIs('staff.articles.*')) bg-white/10 text-white font-semibold @else text-white/60 hover:text-white hover:bg-white/5 @endif">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    আমার আর্টিকেল
                </a>
                <a href="{{ route('staff.articles.create') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded transition @if(request()->routeIs('staff.articles.create')) bg-white/10 text-white font-semibold @else text-white/60 hover:text-white hover:bg-white/5 @endif">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    নতুন আর্টিকেল
                </a>
            </nav>
            <div class="p-3 border-t border-white/10">
                <div class="flex items-center gap-2 px-3 py-2">
                    <span class="text-xs text-white/40 truncate">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="px-3 pt-1">
                    @csrf
                    <button class="text-xs text-white/40 hover:text-white transition">লগআউট</button>
                </form>
            </div>
        </aside>
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white dark:bg-[#1e1e1e] border-b border-[#e0e0e0] dark:border-[#333] h-14 flex items-center px-6 sticky top-0 z-40">
                <button type="button" onclick="document.getElementById('staffSidebar').classList.toggle('hidden')" class="md:hidden mr-3 text-[#666] dark:text-[#999]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-3 w-full">
                    <a href="{{ route('staff.articles.index') }}" class="font-serif font-bold text-lg shrink-0">ET <span class="text-[#E02020]">স্টাফ</span></a>
                    <span class="text-[#ccc] dark:text-[#555] hidden sm:inline">|</span>
                    <a href="/" target="_blank" class="text-xs text-[#999] dark:text-[#777] hover:text-[#E02020] dark:hover:text-[#ff6b6b] transition hidden sm:block">সাইট দেখুন →</a>
                    <div class="ml-auto flex items-center gap-3">
                        <span class="text-xs text-[#666] dark:text-[#999] hidden md:block">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-[#999] dark:text-[#777] hover:text-[#E02020] dark:hover:text-[#ff6b6b] transition">লগআউট</button>
                        </form>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="bg-green-50 dark:bg-green-950/20 border-l-4 border-green-600 p-4 mb-6 text-sm text-green-800 dark:text-green-400">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-950/20 border-l-4 border-[#E02020] p-4 mb-6">
                        <ul class="text-sm text-[#E02020] dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
