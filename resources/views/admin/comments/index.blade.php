@extends('layouts.admin')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <h1 class="text-2xl font-bold">Comments</h1>
        <span class="text-xs text-[#999]">সকল Comments</span>
    </div>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Comments</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">আর্টিকেল</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Users</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Date</th><th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th></tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($comments as $comment)
            <tr class="admin-hover-row">
                <td class="p-3 max-w-xs truncate">{{ Str::limit($comment->body, 60) }}</td>
                <td class="p-3 text-[#666] text-xs">{{ Str::limit($comment->article?->title_bn, 40) }}</td>
                <td class="p-3 text-[#666] text-xs">{{ $comment->user?->name }}</td>
                <td class="p-3">
                    <span class="text-xs px-2 py-0.5
                        @if($comment->status === 'approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @elseif($comment->status === 'rejected') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                        @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                        {{ $comment->status === 'approved' ? 'অনুমোদিত' : ($comment->status === 'rejected' ? 'প্রত্যাখ্যাত' : 'অপেক্ষমান') }}
                    </span>
                </td>
                <td class="p-3 text-[#999] text-xs">{{ $comment->created_at->diffForHumans() }}</td>
                <td class="p-3 text-right whitespace-nowrap">
                    @if($comment->status !== 'approved')
                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">@csrf <button type="submit" class="text-green-600 hover:text-green-800 text-xs mr-2">অনুমোদন</button></form>
                    @endif
                    @if($comment->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" class="inline">@csrf <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-xs mr-2">প্রত্যাখ্যান</button></form>
                    @endif
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-700 text-xs">ডিলিট</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-6 text-center text-[#999]">কোনো Comments নেই।</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $comments->links() }}</div>
@endsection
