<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::orderBy('order')->orderByDesc('created_at')->paginate(20);
        return view('admin.partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('admin.partners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'url' => 'nullable|url|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Partner::create($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner added!');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'url' => 'nullable|url|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo && !preg_match('#^(https?:)?//|data:#i', (string) $partner->logo)) {
                Storage::disk('public')->delete((string) $partner->logo);
            }
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $partner->update($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated!');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        if ($partner->logo && !preg_match('#^(https?:)?//|data:#i', (string) $partner->logo)) {
            Storage::disk('public')->delete((string) $partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted!');
    }
}
