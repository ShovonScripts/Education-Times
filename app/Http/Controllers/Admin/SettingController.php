<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PageView;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $settings = Setting::all()->keyBy('key');
        $tab = $request->tab ?? 'general';

        return view('admin.settings.index', compact('settings', 'tab'));
    }

    public function clearCache(): RedirectResponse
    {
        Artisan::call('optimize:clear');
        Setting::clearCache();
        return back()->with('success', 'All cache cleared! (config, route, view, cache, compiled)');
    }

    public function clearData(): RedirectResponse
    {
        PageView::truncate();
        if (class_exists(ActivityLog::class)) {
            ActivityLog::truncate();
        }
        return back()->with('success', 'Pages ভিউ ও Activity লগ ডাটা সাফ করা হয়েছে!');
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'site_name_bn' => 'nullable|string|max:255',
            'site_name_en' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'site_footer_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'site_loader' => 'nullable|image|mimes:gif,png|max:2048',
            'loader_enabled' => 'nullable|in:0,1',
            'footer_text' => 'nullable|string|max:2000',
            'footer_copyright' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:1000',
            'social_facebook' => 'nullable|url|max:500',
            'social_twitter' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_linkedin' => 'nullable|url|max:500',
            'social_whatsapp' => 'nullable|string|max:50',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
            'google_tag_manager_id' => 'nullable|string|max:50',
            'maintenance_mode' => 'nullable|in:0,1',
            'email_host' => 'nullable|string|max:255',
            'email_port' => 'nullable|string|max:10',
            'email_username' => 'nullable|string|max:255',
            'email_password' => 'nullable|string|max:255',
            'email_encryption' => 'nullable|string|in:tls,ssl,null|max:10',
            'email_from_address' => 'nullable|email|max:255',
            'email_from_name' => 'nullable|string|max:255',

            // Footer editorial
            'editor_name' => 'nullable|string|max:255',
            'editor_designation' => 'nullable|string|max:255',
            'publisher_name' => 'nullable|string|max:255',
            'footer_tagline' => 'nullable|string|max:500',

            // Homepage section controls
            'newsletter_title' => 'nullable|string|max:255',
            'newsletter_pitch' => 'nullable|string|max:1000',
            'hero_breaking_label' => 'nullable|string|max:50',
            'hero_section_titles' => 'nullable|string|max:2000', // JSON map: section_key => label
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if (in_array($key, ['site_logo', 'site_footer_logo', 'site_favicon', 'site_loader']) && $request->hasFile($key)) {
                $old = Setting::get($key);
                if ($old && !preg_match('#^(https?:)?//|data:#i', (string) $old)) {
                    Storage::disk('public')->delete((string) $old);
                }
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                Setting::set($key, $path);
            } elseif (!in_array($key, ['site_logo', 'site_footer_logo', 'site_favicon', 'site_loader'])) {
                Setting::set($key, $value ?? '');
            }
        }

        return redirect()->route('admin.settings.index', ['tab' => $request->tab ?? 'general'])
            ->with('success', 'Settings আপডেট হয়েছে!');
    }
}
