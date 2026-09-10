@extends('layouts.admin')
@section('title', 'Pages Manager')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <h1 class="text-2xl font-bold">Pages Manager</h1>
    </div>
</div>

<div class="admin-card overflow-hidden">
    <table class="admin-table">
        <thead>
            <tr class="admin-table-header">
                <th class="text-left">Pages</th>
                <th class="text-left">স্লাগ</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $page)
            <tr class="admin-hover-row border-t border-[#e0e0e0] dark:border-[#333]">
                <td class="px-6 py-4">
                    <div class="font-medium">{{ $page['title_bn'] }}</div>
                    <div class="text-xs text-[#999]">{{ $page['title_en'] }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-[#999]">{{ $page['slug'] }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.pages.edit', $page['slug']) }}" class="btn-secondary text-xs px-3 py-1.5">এডিট</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
