@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <svg class="h-6 w-6 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <h1 class="text-2xl font-bold">Messages</h1>
        <span class="text-xs text-[#999]">From the contact form</span>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.contacts.index') }}" class="px-3 py-1.5 text-xs font-medium border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition @if(!request('filter')) bg-[#E02020] text-white border-[#E02020] hover:bg-red-700 @endif">All</a>
        <a href="{{ route('admin.contacts.index', ['filter' => 'unread']) }}" class="px-3 py-1.5 text-xs font-medium border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition @if(request('filter') === 'unread') bg-[#E02020] text-white border-[#E02020] hover:bg-red-700 @endif">Unread ({{ $unreadCount }})</a>
        <a href="{{ route('admin.contacts.index', ['filter' => 'unreplied']) }}" class="px-3 py-1.5 text-xs font-medium border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition @if(request('filter') === 'unreplied') bg-[#E02020] text-white border-[#E02020] hover:bg-red-700 @endif">Unreplied ({{ $unrepliedCount }})</a>
        <a href="{{ route('admin.contacts.index', ['filter' => 'replied']) }}" class="px-3 py-1.5 text-xs font-medium border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition @if(request('filter') === 'replied') bg-[#E02020] text-white border-[#E02020] hover:bg-red-700 @endif">Replied</a>
    </div>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Name</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Subject</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Email</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Date</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($contacts as $contact)
            <tr class="admin-hover-row @if(!$contact->read_at) font-semibold @endif">
                <td class="p-3">{{ $contact->name }}</td>
                <td class="p-3 text-[#666] max-w-xs truncate">{{ Str::limit($contact->subject, 50) }}</td>
                <td class="p-3 text-[#666] text-xs">{{ $contact->email }}</td>
                <td class="p-3">
                    @if($contact->replied_at)
                    <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Replied</span>
                    @elseif($contact->read_at)
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Read</span>
                    @else
                    <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Unread</span>
                    @endif
                </td>
                <td class="p-3 text-[#999] text-xs">{{ $contact->created_at->diffForHumans() }}</td>
                <td class="p-3 text-right whitespace-nowrap">
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="text-xs text-[#E02020] hover:underline px-2">View</a>
                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="inline" onsubmit="return confirm('Delete this message?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline px-2">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-10 text-center text-sm text-[#999]">No messages.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($contacts->hasPages())
<div class="mt-6">
    {{ $contacts->links() }}
</div>
@endif
@endsection
