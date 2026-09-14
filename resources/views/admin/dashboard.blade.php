@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#111] dark:text-white">Dashboard</h1>
        <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">Today's Visitors: <strong class="text-[#E02020] dark:text-[#ff6b6b]">{{ number_format($todayViews) }}</strong></p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="btn-danger flex items-center gap-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Article
    </a>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <span class="text-xs text-[#999] dark:text-[#777]">Total</span>
        </div>
        <p class="text-2xl font-bold font-serif text-[#111] dark:text-white">{{ number_format($stats['articles']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">Articles</p>
    </div>

    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-[#999] dark:text-[#777]">Today</span>
        </div>
        <p class="text-2xl font-bold font-serif text-green-600 dark:text-green-400">{{ number_format($stats['today_published']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">Published</p>
    </div>

    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <span class="text-xs text-[#999] dark:text-[#777]">Total</span>
        </div>
        <p class="text-2xl font-bold font-serif text-[#111] dark:text-white">{{ number_format($stats['users']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">Users</p>
    </div>

    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <span class="text-xs text-[#999] dark:text-[#777]">Total</span>
        </div>
        <p class="text-2xl font-bold font-serif text-[#111] dark:text-white">{{ number_format($stats['comments']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">Comments</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-4">
        <p class="text-lg font-bold font-serif text-blue-600 dark:text-blue-400">{{ number_format($stats['drafts']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999]">Drafts</p>
        <div class="mt-2 h-1.5 bg-[#f0f0f0] dark:bg-[#2a2a2a] rounded-full overflow-hidden">
            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $stats['articles'] > 0 ? ($stats['drafts'] / $stats['articles'] * 100) : 0 }}%"></div>
        </div>
    </div>
    <div class="admin-card p-4">
        <p class="text-lg font-bold font-serif text-orange-600 dark:text-orange-400">{{ number_format($stats['scheduled']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999]">Scheduled</p>
        <div class="mt-2 h-1.5 bg-[#f0f0f0] dark:bg-[#2a2a2a] rounded-full overflow-hidden">
            <div class="h-full bg-orange-500 rounded-full" style="width: {{ $stats['articles'] > 0 ? ($stats['scheduled'] / $stats['articles'] * 100) : 0 }}%"></div>
        </div>
    </div>
    <div class="admin-card p-4">
        <p class="text-lg font-bold font-serif text-red-600 dark:text-red-400">{{ number_format($stats['breaking']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999]">Breaking</p>
        <div class="mt-2 h-1.5 bg-[#f0f0f0] dark:bg-[#2a2a2a] rounded-full overflow-hidden">
            <div class="h-full bg-red-500 rounded-full" style="width: {{ $stats['articles'] > 0 ? ($stats['breaking'] / $stats['articles'] * 100) : 0 }}%"></div>
        </div>
    </div>
    <div class="admin-card p-4">
        <p class="text-lg font-bold font-serif text-yellow-600 dark:text-yellow-400">{{ number_format($stats['featured']) }}</p>
        <p class="text-xs text-[#666] dark:text-[#999]">Featured</p>
        <div class="mt-2 h-1.5 bg-[#f0f0f0] dark:bg-[#2a2a2a] rounded-full overflow-hidden">
            <div class="h-full bg-yellow-500 rounded-full" style="width: {{ $stats['articles'] > 0 ? ($stats['featured'] / $stats['articles'] * 100) : 0 }}%"></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 admin-card">
        <div class="flex items-center justify-between px-5 py-3 border-b border-[#e0e0e0] dark:border-[#333]">
            <h2 class="text-sm font-bold text-[#111] dark:text-white">Recent Articles</h2>
            <a href="{{ route('admin.articles.index') }}" class="text-xs text-[#999] dark:text-[#777] hover:text-[#0d0d0d] dark:hover:text-white transition">View All →</a>
        </div>
        <div class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @foreach($recentArticles as $article)
            <div class="flex items-center justify-between px-5 py-3 admin-hover-row text-sm">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    @if($article->is_breaking)<span class="breaking-badge shrink-0">Breaking</span>@endif
                    @if($article->is_featured)<span class="badge-flag bg-yellow-500 text-white shrink-0">Featured</span>@endif
                    <span class="truncate">{{ Str::limit($article->title_bn, 55) }}</span>
                </div>
                <div class="flex items-center gap-3 shrink-0 ml-3">
                    <span class="text-xs px-1.5 py-0.5 {{ match($article->status) { 'published' => 'badge-published', 'scheduled' => 'badge-scheduled', default => 'badge-draft' } }}">
                        {{ $article->status === 'published' ? 'Published' : ($article->status === 'scheduled' ? 'Scheduled' : 'Draft') }}
                    </span>
                    <span class="text-xs text-[#999] dark:text-[#777]">{{ $article->created_at->format('d/m') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="space-y-6">
        <div class="admin-card p-5">
            <h2 class="text-sm font-bold mb-4 text-[#111] dark:text-white flex items-center gap-2">
                <svg class="h-4 w-4 text-[#666] dark:text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Most Read
            </h2>
            <div class="space-y-3">
                @foreach($topViewed->take(5) as $article)
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-xs font-bold text-[#ccc] dark:text-[#555] w-5 shrink-0">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="truncate flex-1 text-[#333] dark:text-[#ccc]">{{ Str::limit($article->title_bn, 35) }}</span>
                    <span class="text-xs text-[#999] dark:text-[#777] shrink-0 font-medium">{{ number_format($article->view_count) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @if($recentComments->isNotEmpty())
        <div class="admin-card p-5">
            <h2 class="text-sm font-bold mb-4 text-[#111] dark:text-white flex items-center gap-2">
                <svg class="h-4 w-4 text-[#666] dark:text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Recent Comments
            </h2>
            <div class="space-y-3">
                @foreach($recentComments->take(5) as $comment)
                <div class="text-sm">
                    <p class="text-xs text-[#666] dark:text-[#999] line-clamp-2">{{ Str::limit($comment->body, 80) }}</p>
                    <p class="text-[10px] text-[#999] dark:text-[#777] mt-1">
                        <span class="font-medium text-[#555] dark:text-[#aaa]">{{ $comment->user?->name }}</span>
                        @if($comment->article?->title_bn)
                        <span class="mx-1">·</span>
                        <span>{{ Str::limit($comment->article->title_bn, 20) }}</span>
                        @endif
                    </p>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.comments.index') }}" class="text-xs text-[#999] dark:text-[#777] hover:text-[#0d0d0d] dark:hover:text-white transition mt-3 inline-block flex items-center gap-1">
                All Comments
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endif
    </div>
</div>

@if($articlesByCategory->isNotEmpty())
<div class="admin-card p-5">
    <h2 class="text-sm font-bold mb-4 text-[#111] dark:text-white flex items-center gap-2">
        <svg class="h-4 w-4 text-[#666] dark:text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        Articles by Category
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @foreach($articlesByCategory as $item)
        <div class="bg-[#f9f9f9] dark:bg-[#1a1a1a] border border-[#e5e5e5] dark:border-[#333] p-3 text-center">
            <p class="text-xs text-[#666] dark:text-[#999] mb-1 truncate">{{ $item->category?->name_bn ?? ' Uncategorized' }}</p>
            <p class="text-lg font-bold font-serif text-[#111] dark:text-white">{{ $item->total }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
