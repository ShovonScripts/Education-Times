@php
$ads = \App\Models\Advertisement::where('position', 'sidebar')
    ->where('is_active', true)
    ->where(function($q) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
    ->where(function($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
    ->orderBy('order')->take(2)->get();
@endphp
@foreach($ads as $ad)
<div class="border border-[#e5e5e5] bg-white">
    @if($ad->type === 'banner')
        <a href="{{ route('admin.ads.click', $ad) }}?url={{ urlencode($ad->link_url) }}" target="_blank" rel="noopener" class="block hover:opacity-90 transition">
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="mx-auto max-w-full h-auto">
        </a>
    @else
        {!! $ad->code !!}
    @endif
    <img src="{{ route('admin.ads.impression', $ad) }}" alt="" class="hidden" width="1" height="1">
</div>
@endforeach
