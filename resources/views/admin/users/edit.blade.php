@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Users তালিকা</span></a>
</div>
<h1 class="font-serif text-2xl font-bold mb-6">Users এডিট: {{ $user->name }}</h1>

<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] p-6 md:p-8">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">ফোন</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">জেলা</label>
                <select name="district_id" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
                    <option value="">—</option>
                    @foreach($districts as $d)
                    <option value="{{ $d->id }}" @selected($user->district_id == $d->id)>{{ $d->name_bn }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">পদবী</label>
                <input type="text" name="designation" value="{{ old('designation', $user->designation) }}"
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">বিদ্যালয়ের Name</label>
                <input type="text" name="school_name" value="{{ old('school_name', $user->school_name) }}"
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">উপজেলা</label>
            <input type="text" name="upazila" value="{{ old('upazila', $user->upazila) }}"
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>

        <div class="border-t border-[#e0e0e0] pt-4">
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_admin" value="0">
                    <input type="checkbox" name="is_admin" value="1" @checked($user->is_admin) class="accent-[#E02020]">
                    <span>Admins</span>
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_editor" value="0">
                    <input type="checkbox" name="is_editor" value="1" @checked($user->is_editor) class="accent-blue-600">
                    <span>এডিটর</span>
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked($user->is_active) class="accent-green-600">
                    <span>Active</span>
                </label>
            </div>
        </div>

        <div class="border-t border-[#e0e0e0] pt-4">
            <p class="text-sm font-medium text-[#666] mb-3">Password পরিবর্তন (শুধুমাত্র পরিবর্তন করতে চাইলে পূরণ করুন)</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] mb-1">নতুন Password</label>
                    <input type="password" name="password" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] mb-1">নিশ্চিত করুন</label>
                    <input type="password" name="password_confirmation" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.users.index') }}" class="border border-[#e0e0e0] text-[#666] px-6 py-2.5 text-sm hover:bg-[#f5f5f5] transition">Cancel</a>
            <button type="submit" class="bg-[#0d0d0d] dark:bg-[#333] text-white px-6 py-2.5 text-sm font-medium hover:bg-black dark:hover:bg-[#444] transition">আপডেট</button>
        </div>
    </form>
</div>

{{-- Quick Role Toggle --}}
<div class="mt-6 bg-white border border-[#e0e0e0] p-5">
    <h3 class="text-xs font-bold uppercase tracking-wider text-[#666] mb-3">দ্রুত রোল পরিবর্তন</h3>
    <div class="flex items-center gap-3">
        @php
            $currentRole = $user->is_admin ? 'admin' : ($user->is_editor ? 'editor' : 'user');
        @endphp
        @foreach(['admin' => 'Admins', 'editor' => 'এডিটর', 'user' => 'Users'] as $role => $label)
        <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}" class="inline">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            <button type="submit" class="px-4 py-2 text-sm border transition
                @if($currentRole === $role) bg-[#0d0d0d] dark:bg-[#333] text-white border-[#0d0d0d] dark:border-[#444]
                @else border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#999] hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] @endif">
                {{ $label }}
            </button>
        </form>
        @endforeach
    </div>
</div>
@endsection
