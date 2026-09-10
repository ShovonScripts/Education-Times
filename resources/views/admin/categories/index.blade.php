@extends('layouts.admin')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        <h1 class="text-2xl font-bold">Categories ও উপ-Categories</h1>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary flex items-center gap-1.5">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        নতুন Categories
    </a>
</div>

@if(session('error'))
<div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-[#E02020] p-4 mb-6 text-sm text-[#E02020]">{{ session('error') }}</div>
@endif

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Categories</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">স্লাগ</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden sm:table-cell">অর্ডার</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">Posts</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($categories as $cat)
            <tr class="admin-hover-row bg-[#fafafa]/50 dark:bg-[#1a1a1a]/50">
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <svg class="h-3 w-3 text-[#E02020] shrink-0" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
                        <span class="font-semibold">{{ $cat->name_bn }}</span>
                        <span class="text-[#ccc] dark:text-[#555] text-xs hidden sm:inline">{{ $cat->name_en }}</span>
                        @if($cat->children->count() > 0)
                        <span class="text-[10px] bg-[#e0e0e0] dark:bg-[#333] text-[#666] dark:text-[#aaa] px-1.5 py-0.5">{{ $cat->children->count() }} টি উপ-Categories</span>
                        @endif
                    </div>
                </td>
                <td class="p-3 text-[#999] dark:text-[#666] text-xs hidden md:table-cell">{{ $cat->slug }}</td>
                <td class="p-3 text-[#666] hidden sm:table-cell">{{ $cat->order }}</td>
                <td class="p-3">
                    <span class="badge-{{ $cat->is_active ? 'published' : 'draft' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span>
                </td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ $cat->articles()->count() }}</td>
                <td class="p-3 text-right">
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-2">এডিট</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-700 text-xs">ডিলিট</button></form>
                </td>
            </tr>
            @foreach($cat->children as $child)
            <tr class="admin-hover-row">
                <td class="p-3 pl-10">
                    <div class="flex items-center gap-2">
                        <svg class="h-3 w-3 text-[#ccc] dark:text-[#555] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="font-medium">{{ $child->name_bn }}</span>
                        <span class="text-[#ccc] dark:text-[#555] text-xs hidden sm:inline">{{ $child->name_en }}</span>
                    </div>
                </td>
                <td class="p-3 text-[#999] dark:text-[#666] text-xs hidden md:table-cell">{{ $child->slug }}</td>
                <td class="p-3 text-[#666] hidden sm:table-cell">{{ $child->order }}</td>
                <td class="p-3">
                    <span class="badge-{{ $child->is_active ? 'published' : 'draft' }}">{{ $child->is_active ? 'Active' : 'Inactive' }}</span>
                </td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ $child->articles()->count() }}</td>
                <td class="p-3 text-right">
                    <a href="{{ route('admin.categories.edit', $child) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-2">এডিট</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $child) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-700 text-xs">ডিলিট</button></form>
                </td>
            </tr>
            @endforeach
            @empty
            <tr><td colspan="6" class="p-8 text-center text-sm text-[#999]">কোনো Categories নেই। প্রথম Categories তৈরি করুন।</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
