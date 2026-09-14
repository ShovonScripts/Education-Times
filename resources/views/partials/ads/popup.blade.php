@php
$popup = \App\Models\Advertisement::where('position', 'popup')
    ->where('is_active', true)
    ->where(function($q) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
    ->where(function($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
    ->orderBy('order')->first();
@endphp
@if($popup)
<div id="popupAd" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="relative w-full max-w-md mx-4 animate-[popupIn_0.3s_ease-out]">
        <div class="relative overflow-hidden shadow-2xl bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333]">
            <button type="button" onclick="closePopup()" class="absolute top-3 right-3 z-10 w-7 h-7 flex items-center justify-center rounded-full bg-black/20 dark:bg-white/20 text-white dark:text-[#e0e0e0] hover:bg-[#E02020] hover:text-white transition-all duration-200" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            @if($popup->type === 'banner')
                <a href="{{ route('admin.ads.click', $popup) }}?url={{ urlencode($popup->link_url) }}" target="_blank" rel="noopener" class="block hover:opacity-90 transition-opacity duration-200">
                    <img src="{{ $popup->image_url }}" alt="{{ $popup->title }}" class="mx-auto w-full" style="max-width:100%;height:auto;">
                </a>
            @else
                {!! $popup->code !!}
            @endif
        </div>
        <img src="{{ route('admin.ads.impression', $popup) }}" alt="" class="hidden" width="1" height="1">
    </div>
</div>
<style>
@keyframes popupIn {
    from { opacity: 0; transform: scale(0.9) translateY(20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var DISMISS_KEY = 'popupAdClosedAt';
    var DISMISS_TTL = 24 * 60 * 60 * 1000; // re-show after 24h
    var dismissedAt = parseInt(localStorage.getItem(DISMISS_KEY) || '0', 10);
    if (!dismissedAt || (Date.now() - dismissedAt) > DISMISS_TTL) {
        localStorage.removeItem(DISMISS_KEY);
        const el = document.getElementById('popupAd');
        if (el) setTimeout(function() { el.classList.remove('hidden'); }, 1000);
    }
});
function closePopup() {
    document.getElementById('popupAd')?.classList.add('hidden');
    localStorage.setItem('popupAdClosedAt', String(Date.now()));
}
</script>
@endif
