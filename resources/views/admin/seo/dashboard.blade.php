@extends('layouts.admin')
@section('title', 'SEO Manager')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <h1 class="text-2xl font-bold">SEO Manager</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $total }} টি Posts, {{ $published }} টি Published</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.seo.bulk-editor') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Bulk SEO
        </a>
        <a href="{{ route('admin.seo.redirects') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Redirects
        </a>
        <a href="{{ route('admin.seo.robots') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            robots.txt
        </a>
        <a href="{{ route('sitemap') }}" target="_blank" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            sitemap.xml
        </a>
    </div>
</div>

<div class="admin-card p-6 mb-6">
    <div class="flex items-center gap-6 flex-wrap">
        <div class="text-center">
            <div class="text-4xl font-bold {{ $score >= 80 ? 'text-green-600' : ($score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">{{ $score }}%</div>
            <div class="text-xs text-[#999] mt-1">SEO স্কোর</div>
        </div>
        <div class="flex-1 min-w-[200px]">
            <div class="h-2 bg-[#f0f0f0] dark:bg-[#333] rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all {{ $score >= 80 ? 'bg-green-500' : ($score >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $score }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-[#999] mt-1">
                <span>{{ $total - $issues[0]['count'] }} টি মেটা টাইটেল আছে</span>
                <span>{{ $total - $issues[1]['count'] }} টি মেটা ডেসক্রিপশন আছে</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($issues as $issue)
    <div class="admin-card p-4">
        <div class="flex items-start justify-between">
            <div>
                <span class="inline-block w-2 h-2 rounded-full mt-1.5 {{ $issue['severity'] === 'high' ? 'bg-red-500' : ($issue['severity'] === 'medium' ? 'bg-yellow-500' : 'bg-blue-500') }}"></span>
                <p class="text-sm font-medium mt-1">{{ $issue['label'] }}</p>
            </div>
            <span class="text-2xl font-bold {{ $issue['count'] > 0 ? ($issue['severity'] === 'high' ? 'text-red-600' : 'text-yellow-600') : 'text-green-600' }}">{{ $issue['count'] }}</span>
        </div>
        <div class="mt-2 h-1.5 bg-[#f0f0f0] dark:bg-[#333] rounded-full overflow-hidden">
            <div class="h-full rounded-full {{ $issue['count'] > 0 ? ($issue['severity'] === 'high' ? 'bg-red-500' : 'bg-yellow-500') : 'bg-green-500' }}" style="width: {{ $total > 0 ? min(100, ($issue['count'] / $total) * 100) : 0 }}%"></div>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <a href="{{ route('admin.seo.bulk-editor', ['seo_issue' => 'no_meta_title']) }}" class="admin-card p-4 admin-hover-row transition flex items-center gap-3">
        <svg class="h-8 w-8 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4H4a1 1 0 00-1 1v2a1 1 0 001 1h16a1 1 0 001-1V5a1 1 0 00-1-1h-3"/></svg>
        <div>
            <p class="text-sm font-medium">মেটা টাইটেলহীন Posts</p>
            <p class="text-xs text-[#999]">{{ $issues[0]['count'] }} টি Posts — এখনই ঠিক করুন</p>
        </div>
    </a>
    <a href="{{ route('admin.seo.bulk-editor', ['seo_issue' => 'no_meta_desc']) }}" class="admin-card p-4 admin-hover-row transition flex items-center gap-3">
        <svg class="h-8 w-8 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <div>
            <p class="text-sm font-medium">ডেসক্রিপশনহীন Posts</p>
            <p class="text-xs text-[#999]">{{ $issues[1]['count'] }} টি Posts — এখনই ঠিক করুন</p>
        </div>
    </a>
    <a href="{{ route('admin.seo.bulk-editor', ['seo_issue' => 'no_keywords']) }}" class="admin-card p-4 admin-hover-row transition flex items-center gap-3">
        <svg class="h-8 w-8 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        <div>
            <p class="text-sm font-medium">Keywordsহীন Posts</p>
            <p class="text-xs text-[#999]">{{ $issues[3]['count'] }} টি Posts — এখনই Add</p>
        </div>
    </a>
</div>

<div class="admin-card p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        <div>
            <p class="text-sm font-medium">301 Redirects</p>
            <p class="text-xs text-[#999]">{{ $activeRedirects }} টি Active / {{ $redirects }} টি মোট</p>
        </div>
    </div>
    <a href="{{ route('admin.seo.redirects') }}" class="text-xs text-[#E02020] hover:text-red-700 font-medium flex items-center gap-1">
        Manage করুন
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
    </a>
</div>
@endsection
