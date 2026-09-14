@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.ads.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Go Back
    </a>
</div>
<div class="flex items-center gap-2 mb-6">
    <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    <h1 class="text-2xl font-bold">Edit Advertisement</h1>
</div>
<div class="admin-card p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.ads.update', $ad) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $ad->title) }}" required class="admin-input w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Position *</label>
            <select name="position" id="adPositionEdit" required class="admin-select w-full">
                <option value="header" data-size="728x90" @selected(old('position', $ad->position) === 'header')>Header</option>
                <option value="sidebar" data-size="300x250 / 160x600" @selected(old('position', $ad->position) === 'sidebar')>Sidebar</option>
                <option value="article_top" data-size="728x90 / 468x60" @selected(old('position', $ad->position) === 'article_top')>Above Article</option>
                <option value="article_bottom" data-size="728x90 / 468x60" @selected(old('position', $ad->position) === 'article_bottom')>Below Article</option>
                <option value="footer" data-size="728x90 / 970x90" @selected(old('position', $ad->position) === 'footer')>Footer</option>
                <option value="popup" data-size="400x300 / 300x250" @selected(old('position', $ad->position) === 'popup')>Popup</option>
            </select>
            <p id="sizeHintEdit" class="text-[11px] text-[#999] dark:text-[#666] mt-1.5 flex items-center gap-1">
                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="sizeHintTextEdit">Recommended size: <strong>728x90</strong> (leaderboard)</span>
            </p>
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Type *</label>
            <select name="type" id="adTypeEdit" class="admin-select w-full">
                <option value="banner" @selected(old('type', $ad->type) === 'banner')>Banner (Image)</option>
                <option value="code" @selected(old('type', $ad->type) === 'code')>Code (HTML/JavaScript)</option>
            </select>
        </div>
        <div id="bannerFieldsEdit" @if($ad->type === 'code') style="display:none" @endif>
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">Image URL</label>
                <input type="url" name="image_url" value="{{ old('image_url', $ad->image_url) }}" class="admin-input w-full">
            </div>
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">Link URL</label>
                <input type="url" name="link_url" value="{{ old('link_url', $ad->link_url) }}" class="admin-input w-full">
            </div>
        </div>
        <div id="codeFieldEdit" @if($ad->type === 'banner') style="display:none" @endif>
            <label class="block text-xs font-medium text-[#666] mb-1">HTML/JS Code</label>
            <textarea name="code" rows="4" class="admin-input w-full font-mono">{{ old('code', $ad->code) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-medium text-[#666] mb-1">Width (px)</label><input type="number" name="width" value="{{ old('width', $ad->width) }}" class="admin-input w-full"></div>
            <div><label class="block text-xs font-medium text-[#666] mb-1">Height (px)</label><input type="number" name="height" value="{{ old('height', $ad->height) }}" class="admin-input w-full"></div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-medium text-[#666] mb-1">Start Date</label><input type="date" name="starts_at" value="{{ old('starts_at', $ad->starts_at?->format('Y-m-d')) }}" class="admin-input w-full"></div>
            <div><label class="block text-xs font-medium text-[#666] mb-1">End Date</label><input type="date" name="ends_at" value="{{ old('ends_at', $ad->ends_at?->format('Y-m-d')) }}" class="admin-input w-full"></div>
        </div>
        <div><label class="block text-xs font-medium text-[#666] mb-1">Order</label><input type="number" name="order" value="{{ old('order', $ad->order) }}" class="admin-input w-full"></div>
        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked($ad->is_active) class="h-4 w-4 accent-[#0d0d0d]">
                <span>Active</span>
            </label>
        </div>
        <div class="pt-2"><button type="submit" class="btn-primary">Update</button></div>
    </form>
</div>
@push('scripts')
<script>
const sizeInfoEdit = {
    header: { label: 'Leaderboard', desc: 'Wide banner — spans full width below the header' },
    sidebar: { label: 'Medium Rectangle / Skyscraper', desc: 'Must fit inside the sidebar column' },
    article_top: { label: 'Leaderboard / Banner', desc: 'Between the article featured image and body' },
    article_bottom: { label: 'Leaderboard / Banner', desc: 'After the article body, before the tags' },
    footer: { label: 'Leaderboard / Super Leaderboard', desc: 'Full width above the footer' },
    popup: { label: 'Responsive Modal', desc: 'Inside the popup window — sized to fit mobile too' },
};
document.getElementById('adPositionEdit')?.addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    var size = opt.getAttribute('data-size');
    var info = sizeInfoEdit[this.value] || { label: '', desc: '' };
    document.getElementById('sizeHintTextEdit').innerHTML = 'Recommended size: <strong>' + size + '</strong> (' + info.label + ')<br><span class="text-[10px] text-[#bbb] dark:text-[#555]">' + info.desc + '</span>';
});
document.getElementById('adTypeEdit')?.addEventListener('change', function() {
    document.getElementById('bannerFieldsEdit').style.display = this.value === 'banner' ? 'block' : 'none';
    document.getElementById('codeFieldEdit').style.display = this.value === 'code' ? 'block' : 'none';
});
document.getElementById('adPositionEdit')?.dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
