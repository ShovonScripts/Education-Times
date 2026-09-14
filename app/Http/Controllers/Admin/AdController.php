<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\Response;

class AdController extends Controller
{
    public function index(Request $request): View
    {
        $query = Advertisement::query();
        if ($request->filled('position') && $request->position !== 'all') {
            $query->where('position', $request->position);
        }
        $ads = $query->orderBy('position')->orderBy('order')->paginate(20);
        $positions = ['header', 'sidebar', 'article_top', 'article_bottom', 'footer', 'popup'];
        return view('admin.ads.index', compact('ads', 'positions'));
    }

    public function create(): View
    {
        return view('admin.ads.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|in:header,sidebar,article_top,article_bottom,footer,popup',
            'type' => 'required|in:banner,code',
            'code' => 'nullable|required_if:type,code|string',
            'image_url' => 'nullable|required_if:type,banner|url|max:500',
            'link_url' => 'nullable|url|max:500',
            'width' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        Advertisement::create($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully!');
    }

    public function edit(Advertisement $ad): View
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, Advertisement $ad): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|in:header,sidebar,article_top,article_bottom,footer,popup',
            'type' => 'required|in:banner,code',
            'code' => 'nullable|required_if:type,code|string',
            'image_url' => 'nullable|required_if:type,banner|url|max:500',
            'link_url' => 'nullable|url|max:500',
            'width' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully!');
    }

    public function toggleActive(Advertisement $ad): RedirectResponse
    {
        $ad->update(['is_active' => !$ad->is_active]);
        return redirect()->route('admin.ads.index', request()->query())->with('success', 'Ad status updated!');
    }

    public function destroy(Advertisement $ad): RedirectResponse
    {
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted successfully!');
    }

    public function click(Advertisement $ad, Request $request): RedirectResponse
    {
        $ip = $request->ip();
        $key = "ad_click_{$ad->id}_{$ip}";
        if (!Cache::has($key)) {
            $ad->increment('clicks');
            Cache::put($key, true, 60);
        }
        $url = $request->query('url', '/');
        if (!str_starts_with($url, '/') || str_starts_with($url, '//')) {
            $url = '/';
        }
        return redirect($url);
    }

    public function impression(Advertisement $ad): Response
    {
        $ip = request()->ip();
        $key = "ad_impression_{$ad->id}_{$ip}";
        if (!Cache::has($key)) {
            $ad->increment('impressions');
            Cache::put($key, true, 60);
        }
        return response('ok', 200);
    }
}
