@extends('layouts.admin')
@section('title', 'Ads')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        <h1 class="text-2xl font-bold">Ads Manager</h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Settings
        </a>
        <a href="{{ route('admin.ads.create') }}" class="btn-primary flex items-center gap-1.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            নতুন Ads
        </a>
    </div>
</div>

{{-- Position Filter Tabs --}}
<div class="flex flex-wrap gap-1 mb-5">
    <a href="{{ route('admin.ads.index') }}"
        class="px-3 py-1.5 text-xs font-medium transition {{ !request('position') || request('position') === 'all' ? 'bg-[#0d0d0d] text-white dark:bg-white dark:text-[#0d0d0d]' : 'bg-[#f5f5f5] dark:bg-[#2a2a2a] text-[#666] dark:text-[#aaa] hover:bg-[#e0e0e0] dark:hover:bg-[#333]' }}">
        সব
    </a>
    @foreach($positions as $pos)
    <a href="{{ route('admin.ads.index', ['position' => $pos]) }}"
        class="px-3 py-1.5 text-xs font-medium transition {{ request('position') === $pos ? 'bg-[#0d0d0d] text-white dark:bg-white dark:text-[#0d0d0d]' : 'bg-[#f5f5f5] dark:bg-[#2a2a2a] text-[#666] dark:text-[#aaa] hover:bg-[#e0e0e0] dark:hover:bg-[#333]' }}">
        {{ $pos === 'header' ? 'হেডার' : ($pos === 'sidebar' ? 'সাইডবার' : ($pos === 'article_top' ? 'আর্টিকেলের উপরে' : ($pos === 'article_bottom' ? 'আর্টিকেলের নিচে' : ($pos === 'footer' ? 'ফুটার' : 'পপআপ')))) }}
    </a>
    @endforeach
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Title</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden sm:table-cell">পজিশন</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">সাইজ</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">টাইপ</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">ইম্প/ক্লিক</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden xl:table-cell">শিডিউল</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($ads as $ad)
            <tr class="admin-hover-row">
                <td class="p-3 font-medium">{{ Str::limit($ad->title, 45) }}</td>
                <td class="p-3 text-[#666] text-xs hidden sm:table-cell">
                    <span class="bg-[#f5f5f5] dark:bg-[#2a2a2a] px-2 py-0.5 text-xs">
                        {{ $ad->position === 'header' ? 'হেডার' : ($ad->position === 'sidebar' ? 'সাইডবার' : ($ad->position === 'article_top' ? 'আর্টিকেলের উপরে' : ($ad->position === 'article_bottom' ? 'আর্টিকেলের নিচে' : ($ad->position === 'footer' ? 'ফুটার' : 'পপআপ')))) }}
                    </span>
                </td>
                <td class="p-3 text-[#999] text-xs hidden md:table-cell">
                    @php $sizes = ['header' => '728x90', 'sidebar' => '300x250', 'article_top' => '728x90', 'article_bottom' => '728x90', 'footer' => '728x90', 'popup' => '400x300']; @endphp
                    <span class="text-[10px] font-mono">{{ $sizes[$ad->position] ?? '—' }}</span>
                    @if($ad->width && $ad->height)
                    <span class="text-[10px] text-[#bbb]">({{ $ad->width }}x{{ $ad->height }})</span>
                    @endif
                </td>
                <td class="p-3 text-[#999] text-xs hidden md:table-cell">
                    <span class="flex items-center gap-1">
                        @if($ad->type === 'banner')
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        ব্যাNoর
                        @else
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        কোড
                        @endif
                    </span>
                </td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ number_format($ad->impressions) }} / {{ number_format($ad->clicks) }}</td>
                <td class="p-3 text-xs hidden xl:table-cell">
                    @php
                        $now = now();
                        $scheduleStatus = 'active';
                        if ($ad->starts_at && $ad->starts_at > $now) {
                            $scheduleStatus = 'upcoming';
                        } elseif ($ad->ends_at && $ad->ends_at < $now) {
                            $scheduleStatus = 'expired';
                        }
                    @endphp
                    @if($scheduleStatus === 'active' && !$ad->starts_at && !$ad->ends_at)
                        <span class="text-[#999]">সর্বদা</span>
                    @elseif($scheduleStatus === 'upcoming')
                        <span class="text-yellow-600 dark:text-yellow-400">{{ $ad->starts_at?->format('d M Y') }}</span>
                    @elseif($scheduleStatus === 'expired')
                        <span class="text-red-500">{{ $ad->ends_at?->format('d M Y') }} পর্যন্ত</span>
                    @else
                        <span class="text-green-600 dark:text-green-400">{{ $ad->starts_at?->format('d M Y') }} - {{ $ad->ends_at?->format('d M Y') }}</span>
                    @endif
                </td>
                <td class="p-3">
                    <span class="badge-{{ $ad->is_active ? 'published' : 'draft' }}">{{ $ad->is_active ? 'Active' : 'Inactive' }}</span>
                </td>
                <td class="p-3 text-right">
                    <form method="POST" action="{{ route('admin.ads.toggle-active', $ad) }}" class="inline mr-2">
                        @csrf
                        <button type="submit" class="text-xs {{ $ad->is_active ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                            {{ $ad->is_active ? 'Inactive' : 'Active' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.ads.edit', $ad) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-2">এডিট</a>
                    <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">ডিলিট</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="p-8 text-center text-sm text-[#999]">কোনো Ads নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $ads->withQueryString()->links() }}</div>
@endsection