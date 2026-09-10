@extends('layouts.admin')
@section('title', 'Scheduled Posts')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <h1 class="text-2xl font-bold">Scheduled Posts</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $articles->total() }} টি Posts Scheduled
            @if($overdue > 0)<span class="text-red-600 font-medium ml-2">{{ $overdue }} টি প্রকাশের সময় পেরিয়ে গেছে</span>@endif
        </p>
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
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Scheduled সময়</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">বাকি</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0]">
            @forelse($articles as $article)
            @php
                $diff = now()->diff($article->published_at);
                $isOverdue = $article->published_at <= now();
                $remaining = $isOverdue ? 'পাবলিশ হওয়া উচিত ছিল!' : ($diff->d > 0 ? $diff->d . ' দিন ' . $diff->h . ' ঘণ্টা' : $diff->h . ' ঘণ্টা ' . $diff->i . ' মিনিট');
            @endphp
            <tr class="admin-hover-row @if($isOverdue) bg-red-50 dark:bg-red-900/20 @endif">
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        @if($isOverdue)
                        <span class="w-2 h-2 bg-red-600 rounded-full shrink-0" title="অতিরিক্ত সময়"></span>
                        @else
                        <span class="w-2 h-2 bg-blue-500 rounded-full shrink-0"></span>
                        @endif
                        <span class="font-medium">{{ Str::limit($article->title_bn, 60) }}</span>
                    </div>
                </td>
                <td class="p-3 text-[#666] text-xs hidden md:table-cell">{{ $article->category?->name_bn }}</td>
                <td class="p-3">
                    <span class="text-xs @if($isOverdue) text-red-600 font-semibold @else text-blue-600 @endif">
                        {{ $article->published_at?->format('d/m/Y H:i') }}
                    </span>
                </td>
                <td class="p-3 text-xs text-[#999] hidden lg:table-cell">{{ $remaining }}</td>
                <td class="p-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        @if($isOverdue)
                        <form method="POST" action="{{ route('admin.posts.update-status', $article) }}" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="published">
                            <button type="submit" class="bg-green-600 text-white px-3 py-1.5 text-xs font-medium hover:bg-green-700 transition">এখনই প্রকাশ</button>
                        </form>
                        @endif
                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-[#666] hover:text-[#0d0d0d] p-1.5 transition" title="সময় পরিবর্তন">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.posts.update-status', $article) }}" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="draft">
                            <button type="submit" class="text-[#666] hover:text-[#E02020] p-1.5 transition" title="Draftয় ফিরান" onclick="return confirm('Scheduled সময় Cancel করে Draftয় ফিরাবেন?')">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-10 text-center text-sm text-[#999]">কোনো Scheduled Posts নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection
