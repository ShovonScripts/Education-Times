@extends('layouts.admin')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        <h1 class="text-2xl font-bold">Archives</h1>
    </div>
    <a href="{{ route('admin.archive.create') }}" class="btn-primary flex items-center gap-1.5">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Archive
    </a>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Title</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Subject</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Year</th><th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">File</th><th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th></tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($documents as $doc)
            <tr class="admin-hover-row">
                <td class="p-3 font-medium max-w-xs truncate">{{ $doc->title_bn }}</td>
                <td class="p-3 text-[#666] text-xs">{{ $doc->subcategory }}</td>
                <td class="p-3 text-[#666] text-xs">{{ $doc->year }}</td>
                <td class="p-3 text-[#999] text-xs truncate max-w-[120px]">{{ basename($doc->file_path) }}</td>
                <td class="p-3 text-right whitespace-nowrap">
                    <a href="{{ route('admin.archive.edit', $doc) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-3">Edit</a>
                    <form method="POST" action="{{ route('admin.archive.destroy', $doc) }}" class="inline" onsubmit="return confirm('Are you sure?')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-6 text-center text-[#999]">No archives yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $documents->links() }}</div>
@endsection
