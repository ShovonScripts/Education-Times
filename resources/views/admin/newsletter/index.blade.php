@extends('layouts.admin')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <h1 class="text-2xl font-bold">Newsletter Subscribers</h1>
    </div>
    <div class="flex items-center gap-3 text-sm">
        <span class="text-green-600 dark:text-green-400">Active: {{ $totalActive }}</span>
        <span class="text-[#999]">|</span>
        <span class="text-red-500 dark:text-red-400">Inactive: {{ $totalInactive }}</span>
    </div>
</div>

<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by email or phone..." class="admin-input flex-1">
    <select name="status" class="admin-select w-40">
        <option value="">All Statuses</option>
        <option value="active" @if(request('status')==='active') selected @endif>Active</option>
        <option value="inactive" @if(request('status')==='inactive') selected @endif>Inactive</option>
    </select>
    <button type="submit" class="btn-primary">Search</button>
</form>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Email</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Phone</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Channel</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Subscribed</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($subscribers as $subscriber)
            <tr class="admin-hover-row">
                <td class="p-3">{{ $subscriber->email ?: '-' }}</td>
                <td class="p-3 text-[#666] text-xs">{{ $subscriber->phone ?: '-' }}</td>
                <td class="p-3">
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">{{ $subscriber->channel === 'email' ? 'Email' : 'WhatsApp' }}</span>
                </td>
                <td class="p-3">
                    @if($subscriber->is_active && !$subscriber->unsubscribed_at)
                        <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                    @else
                        <span class="text-xs px-2 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                    @endif
                </td>
                <td class="p-3 text-[#999] text-xs">{{ $subscriber->created_at->diffForHumans() }}</td>
                <td class="p-3 text-right whitespace-nowrap">
                    <form method="POST" action="{{ route('admin.newsletter.toggle-active', $subscriber) }}" class="inline">@csrf
                        <button type="submit" class="text-xs {{ $subscriber->is_active ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }} mr-2">{{ $subscriber->is_active ? 'Deactivate' : 'Activate' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.newsletter.destroy', $subscriber) }}" class="inline" onsubmit="return confirm('Are you sure?')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-6 text-center text-[#999]">No subscribers.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $subscribers->withQueryString()->links() }}</div>
@endsection
