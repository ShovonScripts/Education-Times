@extends('layouts.admin')
@section('title', 'Breaking নিউজ')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <h1 class="text-2xl font-bold">Breaking নিউজ</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $articles->total() }} টি Breaking নিউজ</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        সব Posts
    </a>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Posts</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">Categories</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">প্রকাশ</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($articles as $article)
            <tr class="admin-hover-row">
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-600 rounded-full animate-pulse shrink-0"></span>
                        <span class="font-medium">{{ Str::limit($article->title_bn, 70) }}</span>
                    </div>
                </td>
                <td class="p-3 text-[#666] text-xs hidden md:table-cell">{{ $article->category?->name_bn }}</td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ $article->published_at?->format('d/m/Y H:i') }}</td>
                <td class="p-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-[#666] hover:text-[#0d0d0d] p-1.5 transition" title="এডিট">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.posts.toggle-flag', $article) }}" class="inline">
                            @csrf
                            <input type="hidden" name="flag" value="is_breaking">
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 transition" title="Breaking থেকে সরান">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-10 text-center text-sm text-[#999]">কোনো Breaking নিউজ নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection
