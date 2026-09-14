@extends('layouts.admin')
@section('title', 'Partners')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <h1 class="text-2xl font-bold">Partners</h1>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="btn-primary flex items-center gap-1.5">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Partner
    </a>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Logo</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Name</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden sm:table-cell">URL</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Order</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($partners as $partner)
            <tr class="admin-hover-row">
                <td class="p-3">
                    <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="h-10 w-auto object-contain bg-white rounded border border-[#e0e0e0]">
                </td>
                <td class="p-3 font-medium">{{ $partner->name }}</td>
                <td class="p-3 text-[#666] text-xs hidden sm:table-cell">
                    @if($partner->url)
                        <a href="{{ $partner->url }}" target="_blank" class="hover:underline">{{ Str::limit($partner->url, 40) }}</a>
                    @else
                        <span class="text-[#bbb]">—</span>
                    @endif
                </td>
                <td class="p-3 text-[#666] text-xs">{{ $partner->order }}</td>
                <td class="p-3">
                    <span class="badge-{{ $partner->is_active ? 'published' : 'draft' }}">{{ $partner->is_active ? 'Active' : 'Inactive' }}</span>
                </td>
                <td class="p-3 text-right">
                    <a href="{{ route('admin.partners.edit', $partner) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-2">Edit</a>
                    <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" class="inline" onsubmit="return confirm('Delete this partner?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-sm text-[#999]">No partners yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $partners->withQueryString()->links() }}</div>
@endsection
