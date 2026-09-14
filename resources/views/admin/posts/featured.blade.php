@extends('layouts.admin')
@section('title', 'Featured Posts')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <h1 class="text-2xl font-bold">Featured Posts</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $articles->total() }} টি Featured Posts</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="border border-[#e0e0e0] text-[#666] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] transition flex items-center gap-1">
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
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">দেখা</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0]">
            @forelse($articles as $article)
            <tr class="admin-hover-row">
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-yellow-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <span class="font-medium">{{ Str::limit($article->title_bn, 70) }}</span>
                    </div>
                </td>
                <td class="p-3 text-[#666] text-xs hidden md:table-cell">{{ $article->category?->name_bn }}</td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ number_format($article->view_counts_sum_views ?? 0) }}</td>
                <td class="p-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-[#666] hover:text-[#0d0d0d] p-1.5 transition" title="এডিট">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.posts.toggle-flag', $article) }}" class="inline">
                            @csrf
                            <input type="hidden" name="flag" value="is_featured">
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 transition" title="Featured থেকে সরান">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-10 text-center text-sm text-[#999]">কোনো Featured Posts নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection
