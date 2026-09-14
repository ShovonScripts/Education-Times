@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.categories.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Go Back</span></a></div>
<h1 class="font-serif text-2xl font-bold mb-6">New Category</h1>
<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">Name (Bengali) *</label>
            <input type="text" name="name_bn" value="{{ old('name_bn') }}" required class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">Name (English) *</label>
            <input type="text" name="name_en" value="{{ old('name_en') }}" required class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">Parent Category (leave empty for top-level)</label>
            <select name="parent_id" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
                <option value="">— Top-Level Category —</option>
                @foreach($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name_bn }} ({{ $parent->name_en }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">Slug (auto-generated if left empty)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] mb-1">Order</label>
            <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="accent-[#0d0d0d]"> Active</label>
        </div>
        <div class="pt-2"><button type="submit" class="bg-[#E02020] text-white px-6 py-2.5 text-sm font-medium hover:bg-red-700 transition">Save</button></div>
    </form>
</div>
@endsection
