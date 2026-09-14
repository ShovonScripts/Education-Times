@extends('layouts.admin')
@section('title', 'Pending Review')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h1 class="text-2xl font-bold">Pending Review</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $counts['submitted'] }} Posts awaiting review</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="border border-[#e0e0e0] text-[#666] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        All Posts
    </a>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Posts</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">Author</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">Submitted</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0]">
            @forelse($articles as $article)
            <tr class="admin-hover-row">
                <td class="p-3">
                    <p class="font-medium">{{ Str::limit($article->title_bn, 60) }}</p>
                    <p class="text-xs text-[#999] mt-0.5">{{ $article->category?->name_bn }}</p>
                </td>
                <td class="p-3 text-[#666] text-xs hidden md:table-cell">{{ $article->author?->name }}</td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ $article->created_at->diffForHumans() }}</td>
                <td class="p-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="bg-[#0d0d0d] dark:bg-[#333] text-white px-4 py-1.5 text-xs font-medium hover:bg-black dark:hover:bg-[#444] transition">Review</a>
                        <form method="POST" action="{{ route('admin.posts.update-status', $article) }}" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="published">
                            <button type="submit" class="bg-green-600 text-white px-4 py-1.5 text-xs font-medium hover:bg-green-700 transition">Publish</button>
                        </form>
                        <form method="POST" action="{{ route('admin.posts.update-status', $article) }}" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="draft">
                            <button type="submit" class="border border-[#e0e0e0] text-[#666] px-4 py-1.5 text-xs font-medium hover:bg-[#f5f5f5] transition">Draft</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-10 text-center text-sm text-[#999]">No Pending Posts</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection
