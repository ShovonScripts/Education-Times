@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.partners.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Partners
    </a>
</div>
<div class="flex items-center gap-2 mb-6">
    <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    <h1 class="text-2xl font-bold">Edit Partner</h1>
</div>
<div class="admin-card p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Partner Name *</label>
            <input type="text" name="name" value="{{ old('name', $partner->name) }}" required class="admin-input w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Logo</label>
            <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="admin-input w-full file:bg-[#f5f5f5] file:border-0 file:px-3 file:py-1.5 file:text-sm file:mr-3">
            @if($partner->logo)
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ Storage::url($partner->logo) }}" class="h-10 border border-[#e0e0e0] bg-white rounded">
                <span class="text-xs text-[#999]">{{ $partner->logo }}</span>
            </div>
            @endif
            <p class="text-xs text-[#999] mt-1">Leave blank to keep existing logo</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Website URL</label>
            <input type="url" name="url" value="{{ old('url', $partner->url) }}" class="admin-input w-full" placeholder="https://example.com">
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Order</label>
            <input type="number" name="order" value="{{ old('order', $partner->order) }}" class="admin-input w-full">
            <p class="text-xs text-[#999] mt-1">Lower numbers appear first</p>
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked($partner->is_active) class="h-4 w-4 accent-[#0d0d0d]">
                <span>Active</span>
            </label>
        </div>
        <div class="pt-2">
            <button type="submit" class="btn-primary">Update Partner</button>
        </div>
    </form>
</div>
@endsection
