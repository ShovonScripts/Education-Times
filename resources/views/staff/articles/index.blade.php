@extends('layouts.staff')
@section('title', 'আমার আর্টিকেল')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-2xl font-bold dark:text-white">আমার আর্টিকেল</h1>
    <a href="{{ route('staff.articles.create') }}" class="bg-[#E02020] dark:bg-[#cc1a1a] text-white px-5 py-2 text-sm font-medium hover:bg-red-700 dark:hover:bg-[#991515] transition">নতুন আর্টিকেল</a>
</div>

<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-[#f5f5f5] dark:bg-[#2a2a2a] border-b border-[#e0e0e0] dark:border-[#333]">
            <tr>
                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0]">শিরোনাম</th>
                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0]">বিভাগ</th>
                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0]">স্ট্যাটাস</th>
                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0]">তারিখ</th>
                <th class="text-right p-3 font-semibold dark:text-[#e0e0e0]">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($articles as $article)
            <tr class="hover:bg-[#fafafa] dark:hover:bg-[#2a2a2a]">
                <td class="p-3 font-medium dark:text-white">{{ Str::limit($article->title_bn, 50) }}</td>
                <td class="p-3 text-[#666] dark:text-[#999]">{{ $article->category?->name_bn }}</td>
                <td class="p-3">
                    <span class="text-xs px-2 py-0.5
                        @if($article->status?->value === 'published') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                        @elseif($article->status?->value === 'submitted') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400
                        @elseif($article->status?->value === 'scheduled') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                        @else bg-gray-100 dark:bg-gray-800 text-[#666] dark:text-[#999] @endif">
                        {{ $article->status?->value === 'published' ? 'প্রকাশিত' : ($article->status?->value === 'submitted' ? 'পর্যালোচনায়' : ($article->status?->value === 'scheduled' ? 'নির্ধারিত' : 'খসড়া')) }}
                    </span>
                </td>
                <td class="p-3 text-[#666] dark:text-[#999] text-xs">{{ $article->updated_at->format('d/m/Y') }}</td>
                <td class="p-3 text-right">
                    @if($article->status !== 'published' && $article->status !== 'submitted')
                    <a href="{{ route('staff.articles.edit', $article) }}" class="text-[#666] dark:text-[#999] hover:text-[#0d0d0d] dark:hover:text-white text-xs mr-3">এডিট</a>
                    <form method="POST" action="{{ route('staff.articles.destroy', $article) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-[#E02020] dark:text-[#ff6b6b] hover:text-red-700 dark:hover:text-[#ff4444] text-xs">ডিলিট</button>
                    </form>
                    @else
                    <span class="text-[#ccc] dark:text-[#555] text-xs">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-6 text-center text-sm text-[#999] dark:text-[#777]">কোনো আর্টিকেল নেই।</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection
