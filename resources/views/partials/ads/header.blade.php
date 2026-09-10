@php
$ads = \App\Models\Advertisement::where('position', 'header')
    ->where('is_active', true)
    ->where(function($q) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
    ->where(function($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
    ->orderBy('order')->get();
@endphp
@foreach($ads as $ad)
<div class="max-w-[1240px] mx-auto px-4 py-3 flex justify-center">
    @if($ad->type === 'banner')
        <a href="{{ route('admin.ads.click', $ad) }}?url={{ urlencode($ad->link_url) }}" target="_blank" rel="noopener" class="block">
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="max-w-full h-auto" style="{{ $ad->width ? 'width:'.$ad->width.'px;' : '' }}">
        </a>
    @else
        {!! $ad->code !!}
    @endif
    <img src="{{ route('admin.ads.impression', $ad) }}" alt="" class="hidden" width="1" height="1">
</div>
@endforeach
