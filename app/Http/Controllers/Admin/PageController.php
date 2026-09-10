<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = [
            ['slug' => 'privacy', 'title_bn' => 'প্রাইভেসি পলিসি', 'title_en' => 'Privacy Policy'],
            ['slug' => 'terms', 'title_bn' => 'শর্তাবলী ও নিয়মাবলী', 'title_en' => 'Terms & Conditions'],
        ];

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(string $slug): View
    {
        $allowed = ['privacy', 'terms'];

        if (!in_array($slug, $allowed)) {
            abort(404);
        }

        $content = Setting::get('page_' . $slug, '');
        $titles = [
            'privacy' => ['bn' => 'প্রাইভেসি পলিসি', 'en' => 'Privacy Policy'],
            'terms' => ['bn' => 'শর্তাবলী ও নিয়মাবলী', 'en' => 'Terms & Conditions'],
        ];

        return view('admin.pages.edit', [
            'slug' => $slug,
            'content' => $content,
            'titleBn' => $titles[$slug]['bn'],
            'titleEn' => $titles[$slug]['en'],
        ]);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $allowed = ['privacy', 'terms'];

        if (!in_array($slug, $allowed)) {
            abort(404);
        }

        $request->validate([
            'content' => 'nullable|string',
        ]);

        Setting::set('page_' . $slug, $request->input('content', ''));

        return redirect()->route('admin.pages.index')
            ->with('success', 'Pages Content আপডেট হয়েছে!');
    }
}
