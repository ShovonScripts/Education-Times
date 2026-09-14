<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Contact;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Bengali relative times everywhere ("২ ঘণ্টা আগে") instead of English diffForHumans
        Carbon::setLocale('bn');

        $sidebarData = Cache::remember('admin_sidebar_counts', 60, function () {
            $statusCounts = Article::select('status', DB::raw('count(*) as c'))
                ->groupBy('status')
                ->pluck('c', 'status');

            return [
                'pendingCount' => $statusCounts['submitted'] ?? 0,
                'scheduledCount' => $statusCounts['scheduled'] ?? 0,
                'adminCount' => User::where('is_admin', true)->count(),
                'unreadContactCount' => Schema::hasTable('contacts') ? Contact::unread()->count() : 0,
                'subscriberCount' => Schema::hasTable('newsletter_subscribers') ? NewsletterSubscriber::active()->count() : 0,
                'siteLogo' => Setting::get('site_logo', ''),
                'siteNameBn' => Setting::get('site_name_bn', config('app.name')),
            ];
        });

        View::composer('admin.partials.sidebar', function ($view) use ($sidebarData) {
            $view->with($sidebarData);
        });

        View::composer('layouts.admin', function ($view) use ($sidebarData) {
            $view->with([
                'siteLogo' => $sidebarData['siteLogo'],
                'siteNameBn' => $sidebarData['siteNameBn'],
            ]);
        });

        View::composer('partials.header', function ($view) {
            $view->with('navCategories', Category::where('is_active', true)->orderBy('order')->get());
            $view->with('siteNameBn', Setting::get('site_name_bn', 'প্রাথমিক শিক্ষা নিউজ'));
            $view->with('siteLogo', Setting::get('site_logo', ''));
            $view->with('socialFacebook', Setting::get('social_facebook', ''));
            $view->with('socialTwitter', Setting::get('social_twitter', ''));
            $view->with('socialYoutube', Setting::get('social_youtube', ''));
            $view->with('socialInstagram', Setting::get('social_instagram', ''));
            $view->with('socialLinkedin', Setting::get('social_linkedin', ''));
            $view->with('socialWhatsapp', Setting::get('social_whatsapp', ''));
        });

        View::composer('partials.footer', function ($view) {
            $view->with('footerCategories', Category::where('is_active', true)->orderBy('order')->get());
            $view->with('socialFacebook', Setting::get('social_facebook', ''));
            $view->with('socialTwitter', Setting::get('social_twitter', ''));
            $view->with('socialYoutube', Setting::get('social_youtube', ''));
            $view->with('socialInstagram', Setting::get('social_instagram', ''));
            $view->with('socialLinkedin', Setting::get('social_linkedin', ''));
            $view->with('socialWhatsapp', Setting::get('social_whatsapp', ''));
            $view->with('siteFooterLogo', Setting::get('site_footer_logo', ''));
        });
    }
}
