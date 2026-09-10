@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Users তালিকা</span></a>
</div>
<h1 class="font-serif text-2xl font-bold mb-6">নতুন Users</h1>

<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] p-6 md:p-8">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">ফোন</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">জেলা</label>
                <select name="district_id" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
                    <option value="">—</option>
                    @foreach($districts as $d)
                    <option value="{{ $d->id }}" @selected(old('district_id') == $d->id)>{{ $d->name_bn }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">পদবী</label>
                <input type="text" name="designation" value="{{ old('designation') }}"
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] mb-1">বিদ্যালয়ের Name</label>
                <input type="text" name="school_name" value="{{ old('school_name') }}"
                    class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">উপজেলা</label>
            <input type="text" name="upazila" value="{{ old('upazila') }}"
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>

        <div class="border-t border-[#e0e0e0] pt-4">
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_admin" value="0">
                    <input type="checkbox" name="is_admin" value="1" class="accent-[#E02020]">
                    <span>Admins</span>
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_editor" value="0">
                    <input type="checkbox" name="is_editor" value="1" class="accent-blue-600">
                    <span>এডিটর</span>
                </label>
            </div>
        </div>

        <div class="border-t border-[#e0e0e0] pt-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] mb-1">Password *</label>
                    <input type="password" name="password" required
                        class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] mb-1">Password নিশ্চিত করুন *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.users.index') }}" class="border border-[#e0e0e0] text-[#666] px-6 py-2.5 text-sm hover:bg-[#f5f5f5] transition">Cancel</a>
            <button type="submit" class="bg-[#0d0d0d] dark:bg-[#333] text-white px-6 py-2.5 text-sm font-medium hover:bg-black dark:hover:bg-[#444] transition">তৈরি করুন</button>
        </div>
    </form>
</div>
@endsection
