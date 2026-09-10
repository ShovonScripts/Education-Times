@extends('layouts.admin')
@section('title', 'Messages - ' . $contact->subject)
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.contacts.index') }}" class="text-[#999] hover:text-[#E02020] transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold">Messages</h1>
        <span class="text-xs text-[#999]">#{{ $contact->id }}</span>
    </div>
    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Messagesটি ডিলিট করবেন?')">
        @csrf @method('DELETE')
        <button type="submit" class="text-xs text-red-500 hover:underline flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            ডিলিট
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Message --}}
        <div class="admin-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold dark:text-white">{{ $contact->subject }}</h2>
                    <p class="text-xs text-[#999] mt-1">{{ $contact->created_at->format('M d, Y - h:i A') }}</p>
                </div>
                @if($contact->read_at)
                <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 shrink-0">পঠিত</span>
                @endif
            </div>
            <div class="bg-[#fafafa] dark:bg-[#2a2a2a] p-4 border border-[#e0e0e0] dark:border-[#444]">
                <p class="text-sm leading-relaxed dark:text-[#e0e0e0] whitespace-pre-wrap">{{ $contact->message }}</p>
            </div>
        </div>

        {{-- Reply --}}
        @if($contact->reply)
        <div class="admin-card p-6 border-l-4 border-l-green-500">
            <div class="flex items-center gap-2 mb-3">
                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                <h3 class="text-sm font-bold dark:text-white">আপNoর জবাব</h3>
                <span class="text-xs text-[#999]">{{ $contact->replied_at->format('M d, Y - h:i A') }}</span>
            </div>
            <div class="bg-green-50 dark:bg-green-900/10 p-4 border border-green-200 dark:border-green-800">
                <p class="text-sm leading-relaxed dark:text-[#e0e0e0] whitespace-pre-wrap">{{ $contact->reply }}</p>
            </div>
        </div>
        @endif

        {{-- Reply Form --}}
        @if(!$contact->reply)
        <div class="admin-card p-6">
            <h3 class="text-sm font-bold mb-4 dark:text-white">জবাব দিন</h3>
            <form method="POST" action="{{ route('admin.contacts.reply', $contact) }}">
                @csrf
                <textarea name="reply" rows="6" class="w-full border border-[#e0e0e0] dark:border-[#444] bg-white dark:bg-[#2a2a2a] text-sm px-3 py-2.5 focus:border-[#E02020] dark:focus:border-[#ff6b6b] focus:outline-none dark:text-white resize-y" placeholder="আপNoর জবাব লিখুন..." required></textarea>
                @error('reply') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-[#E02020] hover:bg-red-700 text-white text-xs font-medium px-5 py-2.5 transition">জবাব পাঠান</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    {{-- Sender Info --}}
    <div>
        <div class="admin-card p-5 space-y-4">
            <h3 class="text-sm font-bold dark:text-white">প্রেরকের তথ্য</h3>
            <div>
                <p class="text-xs text-[#999]">Name</p>
                <p class="text-sm dark:text-white">{{ $contact->name }}</p>
            </div>
            <div>
                <p class="text-xs text-[#999]">Email</p>
                <p class="text-sm">
                    <a href="mailto:{{ $contact->email }}" class="text-[#E02020] hover:underline">{{ $contact->email }}</a>
                </p>
            </div>
            @if($contact->phone)
            <div>
                <p class="text-xs text-[#999]">ফোন</p>
                <p class="text-sm dark:text-white">{{ $contact->phone }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs text-[#999]">পাঠানোর সময়</p>
                <p class="text-sm dark:text-white">{{ $contact->created_at->format('M d, Y - h:i A') }}</p>
            </div>
            @if($contact->read_at)
            <div>
                <p class="text-xs text-[#999]">পঠিত হয়েছে</p>
                <p class="text-sm dark:text-white">{{ $contact->read_at->format('M d, Y - h:i A') }}</p>
            </div>
            @endif
            @if($contact->replied_at)
            <div>
                <p class="text-xs text-[#999]">জবাব দেওয়া হয়েছে</p>
                <p class="text-sm dark:text-white">{{ $contact->replied_at->format('M d, Y - h:i A') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
