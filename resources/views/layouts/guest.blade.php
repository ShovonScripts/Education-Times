<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::get('site_favicon') ? Storage::url(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#f5f5f5] text-[#111] font-sans antialiased min-h-screen">
    @yield('content')
    @stack('scripts')
</body>
</html>
