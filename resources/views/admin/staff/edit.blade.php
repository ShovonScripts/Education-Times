@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.staff.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Go Back</span></a></div>
<h1 class="font-serif text-2xl font-bold mb-6">Staff এডিট</h1>
<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.staff.update', $staff) }}" class="space-y-4">
        @csrf @method('PUT')
        <div><label class="block text-sm font-medium text-[#666] mb-1">Name (বাংলা) *</label><input type="text" name="name_bn" value="{{ old('name_bn', $staff->name_bn) }}" required class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">Name (English)</label><input type="text" name="name_en" value="{{ old('name_en', $staff->name_en) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">পদবী (বাংলা) *</label><input type="text" name="designation_bn" value="{{ old('designation_bn', $staff->designation_bn) }}" required class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">Designation (English)</label><input type="text" name="designation_en" value="{{ old('designation_en', $staff->designation_en) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">টাইপ *</label>
            <select name="staff_type" required class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
                <option value="editor" @selected($staff->staff_type == 'editor')>সম্পাদক</option>
                <option value="reporter" @selected($staff->staff_type == 'reporter')>প্রতিবেদক</option>
                <option value="advisor" @selected($staff->staff_type == 'advisor')>উপদেষ্টা</option>
                <option value="columnist" @selected($staff->staff_type == 'columnist')>কলামিস্ট</option>
                <option value="correspondent" @selected($staff->staff_type == 'correspondent')>জেলা প্রতিবেদক</option>
                <option value="management" @selected($staff->staff_type == 'management')>ব্যবস্থাপনা</option>
            </select>
        </div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">বায়ো (বাংলা)</label><textarea name="bio_bn" rows="3" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">{{ old('bio_bn', $staff->bio_bn) }}</textarea></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">Bio (English)</label><textarea name="bio_en" rows="3" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">{{ old('bio_en', $staff->bio_en) }}</textarea></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">ফটো URL</label><input type="text" name="photo" value="{{ old('photo', $staff->photo) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-[#666] mb-1">Email</label><input type="email" name="email" value="{{ old('email', $staff->email) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
            <div><label class="block text-sm font-medium text-[#666] mb-1">ফোন</label><input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        </div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">অর্ডার</label><input type="number" name="order" value="{{ old('order', $staff->order) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked($staff->is_active) class="accent-[#0d0d0d]"> Active</label></div>
        <div class="pt-2"><button type="submit" class="bg-[#E02020] text-white px-6 py-2.5 text-sm font-medium hover:bg-red-700 transition">আপডেট</button></div>
    </form>
</div>
@endsection
