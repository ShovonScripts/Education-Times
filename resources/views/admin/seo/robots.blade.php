@extends('layouts.admin')
@section('title', 'robots.txt Editor')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <h1 class="text-2xl font-bold">robots.txt Editor</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">Current robots.txt content shown below — directives for search engine crawlers</p>
    </div>
    <a href="{{ route('admin.seo.dashboard') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        SEO Dashboard
    </a>
</div>

<div class="admin-card p-6">
    <form method="POST" action="{{ route('admin.seo.robots.update') }}">
        @csrf
        <textarea name="content" rows="20" class="admin-input w-full p-4 text-sm font-mono">{{ $robots }}</textarea>
        <p class="text-xs text-[#999] mt-2">Warning: incorrect configuration can block your site from search engines.</p>
        <button type="submit" class="btn-primary mt-4">Save</button>
    </form>
</div>
<div class="mt-4 admin-card p-4">
    <h3 class="text-xs font-bold uppercase tracking-wider text-[#666] mb-2">Available Routes</h3>
    <pre class="text-xs text-[#999]">{{ url('/sitemap.xml') }}</pre>
</div>
@endsection
