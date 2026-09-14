@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.partners.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Partners
    </a>
</div>
<div class="flex items-center gap-2 mb-6">
    <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    <h1 class="text-2xl font-bold">New Partner</h1>
</div>
<div class="admin-card p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Partner Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="admin-input w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Logo *</label>
            <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" required class="admin-input w-full file:bg-[#f5f5f5] file:border-0 file:px-3 file:py-1.5 file:text-sm file:mr-3">
            <p class="text-xs text-[#999] mt-1">Recommended: transparent PNG, max 2MB</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Website URL</label>
            <input type="url" name="url" value="{{ old('url') }}" class="admin-input w-full" placeholder="https://example.com">
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Order</label>
            <input type="number" name="order" value="{{ old('order', 0) }}" class="admin-input w-full">
            <p class="text-xs text-[#999] mt-1">Lower numbers appear first</p>
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 accent-[#0d0d0d]">
                <span>Active</span>
            </label>
        </div>
        <div class="pt-2">
            <button type="submit" class="btn-primary">Save Partner</button>
        </div>
    </form>
</div>
@endsection
