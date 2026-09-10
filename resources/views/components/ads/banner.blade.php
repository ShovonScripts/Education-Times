@props([
    'width' => 728,
    'height' => 90,
    'label' => 'বিজ্ঞাপন',
    'position' => null,
])

@php
    $ad = null;
    if ($position) {
        $ad = \App\Models\Advertisement::where('position', $position)
            ->where('is_active', true)
            ->where(function($q) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
            ->where(function($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
            ->orderBy('order')
            ->first();
    }
@endphp

<div class="my-6">
    @if($ad)
        <a href="{{ route('admin.ads.click', $ad) }}?url={{ urlencode($ad->link_url) }}" target="_blank" rel="noopener" class="block">
            @if($ad->type === 'banner')
                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="mx-auto max-w-full h-auto">
            @else
                {!! $ad->code !!}
            @endif
        </a>
        <img src="{{ route('admin.ads.impression', $ad) }}" alt="" class="hidden" width="1" height="1">
    @else
        <div class="border border-dashed border-[#ccc] bg-[#fafafa] flex items-center justify-center mx-auto" style="max-width: {{ $width }}px; height: {{ $height }}px;">
            <div class="text-center text-[#999]">
                <p class="text-[10px] font-bold uppercase tracking-widest">{{ $label }}</p>
                <p class="text-[10px] mt-0.5">{{ $width }} × {{ $height }}</p>
            </div>
        </div>
    @endif
</div>
