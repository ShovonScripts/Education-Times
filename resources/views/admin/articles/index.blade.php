@extends('layouts.admin')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="h-6 w-6 text-[#E02020]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <h1 class="text-2xl font-bold">আর্টিকেল</h1>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="bg-[#E02020] text-white px-5 py-2 text-sm font-medium hover:bg-red-700 transition flex items-center gap-1.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Article
        </a>
    </div>

    <div class="flex gap-1 mb-4 text-sm flex-wrap">
        <a href="{{ route('admin.articles.index') }}" class="px-3 py-1.5 border border-[#e0e0e0] dark:border-[#444] @if(!request('status')) bg-[#0d0d0d] text-white dark:bg-white dark:text-black @else text-[#666] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif transition">সব</a>
        <a href="{{ route('admin.articles.index', ['status' => 'published']) }}" class="px-3 py-1.5 border border-[#e0e0e0] dark:border-[#444] @if(request('status') === 'published') bg-[#0d0d0d] text-white dark:bg-white dark:text-black @else text-[#666] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif transition">Published</a>
        <a href="{{ route('admin.articles.index', ['status' => 'submitted']) }}" class="px-3 py-1.5 border border-yellow-400 dark:border-yellow-600 @if(request('status') === 'submitted') bg-yellow-400 text-black @else text-[#666] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif transition font-medium">Pending Review</a>
        <a href="{{ route('admin.articles.index', ['status' => 'draft']) }}" class="px-3 py-1.5 border border-[#e0e0e0] dark:border-[#444] @if(request('status') === 'draft') bg-[#0d0d0d] text-white dark:bg-white dark:text-black @else text-[#666] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif transition">Draft</a>
        <a href="{{ route('admin.articles.index', ['status' => 'scheduled']) }}" class="px-3 py-1.5 border border-[#e0e0e0] dark:border-[#444] @if(request('status') === 'scheduled') bg-[#0d0d0d] text-white dark:bg-white dark:text-black @else text-[#666] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif transition">Scheduled</a>
        <a href="{{ route('admin.articles.index', ['status' => 'archived']) }}" class="px-3 py-1.5 border border-[#e0e0e0] dark:border-[#444] @if(request('status') === 'archived') bg-[#0d0d0d] text-white dark:bg-white dark:text-black @else text-[#666] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif transition">আর্কাইভ</a>
    </div>

    <div class="admin-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="admin-table-header">
                <tr>
                    <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Title</th>
                    <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Categories</th>
                    <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Author</th>
                    <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                    <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Date</th>
                    <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
                @foreach($articles as $article)
                <tr class="admin-hover-row">
                    <td class="p-3 font-medium">{{ Str::limit($article->title_bn, 50) }}</td>
                    <td class="p-3 text-[#666] text-xs">{{ $article->category?->name_bn }}</td>
                    <td class="p-3 text-[#666] text-xs">{{ $article->staffs->map(fn($s) => $s->name_bn)->join(', ') ?: $article->author?->name }}</td>
                    <td class="p-3">
                        <span class="badge-{{ $article->status }}">
                            {{ $article->status?->value === 'published' ? 'Published' : ($article->status?->value === 'submitted' ? 'Pending Review' : ($article->status?->value === 'scheduled' ? 'Scheduled' : 'Draft')) }}
                        </span>
                    </td>
                    <td class="p-3 text-[#666] text-xs">{{ $article->published_at?->format('d/m/Y H:i') ?? $article->created_at->format('d/m/Y') }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-3">এডিট</a>
                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">ডিলিট</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $articles->links() }}</div>
@endsection
