@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.ads.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Go Back
    </a>
</div>
<div class="flex items-center gap-2 mb-6">
    <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    <h1 class="text-2xl font-bold">নতুন Ads</h1>
</div>
<div class="admin-card p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.ads.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="admin-input w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">পজিশন *</label>
            <select name="position" id="adPosition" required class="admin-select w-full">
                <option value="header" data-size="728x90" @selected(old('position') === 'header')>হেডার</option>
                <option value="sidebar" data-size="300x250 / 160x600" @selected(old('position') === 'sidebar')>সাইডবার</option>
                <option value="article_top" data-size="728x90 / 468x60" @selected(old('position') === 'article_top')>আর্টিকেলের উপরে</option>
                <option value="article_bottom" data-size="728x90 / 468x60" @selected(old('position') === 'article_bottom')>আর্টিকেলের নিচে</option>
                <option value="footer" data-size="728x90 / 970x90" @selected(old('position') === 'footer')>ফুটার</option>
                <option value="popup" data-size="400x300 / 300x250" @selected(old('position') === 'popup')>পপআপ</option>
            </select>
            <p id="sizeHint" class="text-[11px] text-[#999] dark:text-[#666] mt-1.5 flex items-center gap-1">
                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="sizeHintText">প্রস্তাবিত সাইজ: <strong>728x90</strong> (লিডারবোর্ড)</span>
            </p>
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">টাইপ *</label>
            <select name="type" id="adType" required class="admin-select w-full">
                <option value="banner" @selected(old('type') === 'banner')>ব্যাNoর (Image)</option>
                <option value="code" @selected(old('type') === 'code')>কোড (HTML/JavaScript)</option>
            </select>
        </div>
        <div id="bannerFields">
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">Imageর URL *</label>
                <input type="url" name="image_url" value="{{ old('image_url') }}" class="admin-input w-full" placeholder="https://example.com/ad-banner.jpg">
            </div>
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">Link URL</label>
                <input type="url" name="link_url" value="{{ old('link_url') }}" class="admin-input w-full" placeholder="https://example.com">
            </div>
        </div>
        <div id="codeField" style="display:none">
            <label class="block text-xs font-medium text-[#666] mb-1">HTML/JS কোড *</label>
            <textarea name="code" rows="4" class="admin-input w-full font-mono">{{ old('code') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">প্রস্থ (px)</label>
                <input type="number" name="width" value="{{ old('width') }}" class="admin-input w-full">
            </div>
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">উচ্চতা (px)</label>
                <input type="number" name="height" value="{{ old('height') }}" class="admin-input w-full">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">শুরুর Date</label>
                <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="admin-input w-full">
            </div>
            <div>
                <label class="block text-xs font-medium text-[#666] mb-1">শেষের Date</label>
                <input type="date" name="ends_at" value="{{ old('ends_at') }}" class="admin-input w-full">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-[#666] mb-1">অর্ডার</label>
            <input type="number" name="order" value="{{ old('order', 0) }}" class="admin-input w-full">
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 accent-[#0d0d0d]">
                <span>Active</span>
            </label>
        </div>
        <div class="pt-2">
            <button type="submit" class="btn-primary">সংরক্ষণ</button>
        </div>
    </form>
</div>
@push('scripts')
<script>
const sizeInfo = {
    header: { label: 'লিডারবোর্ড', desc: 'ওয়াইড ব্যাNoর — হেডারের নিচে পুরো প্রস্থে দেখায়' },
    sidebar: { label: 'Mediaম রেক্ট্যাঙ্গেল / স্কাইস্ক্র্যাপার', desc: 'সাইডবার কলামের ভিতরে ফিট করতে হবে' },
    article_top: { label: 'লিডারবোর্ড / ব্যাNoর', desc: 'আর্টিকেলের ফিচার্ড ইমেজ ও বডির মাঝে' },
    article_bottom: { label: 'লিডারবোর্ড / ব্যাNoর', desc: 'আর্টিকেল বডির পরে, Tagsসের আগে' },
    footer: { label: 'লিডারবোর্ড / সুপার লিডারবোর্ড', desc: 'ফুটারের উপরে পুরো প্রস্থে' },
    popup: { label: 'রেসপন্সিভ মডাল', desc: 'পপআপ উইন্ডোর ভিতরে — মোবাইলেও ফিট হবে এমন সাইজ' },
};
document.getElementById('adPosition')?.addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    var size = opt.getAttribute('data-size');
    var info = sizeInfo[this.value] || { label: '', desc: '' };
    document.getElementById('sizeHintText').innerHTML = 'প্রস্তাবিত সাইজ: <strong>' + size + '</strong> (' + info.label + ')<br><span class="text-[10px] text-[#bbb] dark:text-[#555]">' + info.desc + '</span>';
});
document.getElementById('adType')?.addEventListener('change', function() {
    document.getElementById('bannerFields').style.display = this.value === 'banner' ? 'block' : 'none';
    document.getElementById('codeField').style.display = this.value === 'code' ? 'block' : 'none';
});
document.getElementById('adPosition')?.dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
