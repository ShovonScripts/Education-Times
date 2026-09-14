@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.archive.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Go Back</span></a></div>
<h1 class="font-serif text-2xl font-bold mb-6">New Archive</h1>
<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.archive.store') }}" class="space-y-4">
        @csrf
        <div><label class="block text-sm font-medium text-[#666] mb-1">Title (Bangla) *</label><input type="text" name="title_bn" value="{{ old('title_bn') }}" required class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">Title (English)</label><input type="text" name="title_en" value="{{ old('title_en') }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">Description</label><textarea name="description_bn" rows="3" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">{{ old('description_bn') }}</textarea></div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">File Path *</label><input type="text" name="file_path" value="{{ old('file_path') }}" required placeholder="storage/filename.pdf" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div class="grid grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-[#666] mb-1">Year</label><select name="year" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]"><option value="">—</option>@foreach($years as $y)<option value="{{ $y }}" @selected(old('year') == $y)>{{ $y }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-[#666] mb-1">Subject</label><input type="text" name="subcategory" value="{{ old('subcategory') }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
            <div><label class="block text-sm font-medium text-[#666] mb-1">File Type</label><input type="text" name="file_type" value="{{ old('file_type') }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        </div>
        <div><label class="block text-sm font-medium text-[#666] mb-1">File Size (bytes)</label><input type="number" name="file_size" value="{{ old('file_size') }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]"></div>
        <div class="pt-2"><button type="submit" class="bg-[#E02020] text-white px-6 py-2.5 text-sm font-medium hover:bg-red-700 transition">Save</button></div>
    </form>
</div>
@endsection
