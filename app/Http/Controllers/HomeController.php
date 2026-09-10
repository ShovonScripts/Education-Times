<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Category;
use App\Models\Setting;
use App\Models\PageView;
use App\Enums\ArticleStatus;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(): View
    {
        $cacheTtl = 300; // 5 minutes

        $categories = Cache::remember('home_categories', $cacheTtl, function () {
            $activeCategories = Category::where('is_active', true)->orderBy('order')->get();
            foreach ($activeCategories as $category) {
                $category->articles = Article::where('status', ArticleStatus::PUBLISHED->value)
                    ->where('category_id', $category->id)
                    ->select('id', 'category_id', 'title_bn', 'slug', 'excerpt_bn', 'body_bn', 'featured_image', 'video_url', 'is_editor_pick', 'published_at')
                    ->with('staffs:id,name_bn')
                    ->latest('published_at')
                    ->take(4)
                    ->get();
            }
            return $activeCategories->filter(fn($c) => $c->articles->isNotEmpty());
        });

        $leadStory = Cache::remember('home_lead_story', $cacheTtl, function () {
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->where('is_editor_pick', true)
                ->with(['author:id,name', 'category', 'district', 'staffs'])
                ->latest('published_at')
                ->first()
                ?? Article::where('status', ArticleStatus::PUBLISHED->value)
                    ->with(['author:id,name', 'category', 'staffs'])
                    ->latest('published_at')
                    ->first();
        });

        $featuredStories = Cache::remember('home_featured_stories', $cacheTtl, function () use ($leadStory) {
            $stories = Article::where('status', ArticleStatus::PUBLISHED->value)
                ->where('is_featured', true)
                ->where('id', '!=', $leadStory?->id)
                ->with(['category', 'staffs'])
                ->latest('published_at')
                ->take(4)
                ->get();

            if ($stories->isEmpty()) {
                return Article::where('status', ArticleStatus::PUBLISHED->value)
                    ->where('id', '!=', $leadStory?->id)
                    ->with(['category', 'staffs'])
                    ->latest('published_at')
                    ->take(4)
                    ->get();
            }
            return $stories;
        });

        $sliderArticles = Cache::remember('home_slider_articles', $cacheTtl, function () {
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->where('is_slider', true)
                ->with(['category', 'staffs'])
                ->orderBy('slider_order')
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
        });

        $breakingStories = Cache::remember('home_breaking_stories', 60, function () {
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->where('is_breaking', true)
                ->latest('published_at')
                ->take(5)
                ->get();
        });

        $mostRead = Cache::remember('home_most_read', 3600, function () {
            // Cache most read for longer (1 hour) as it requires table scan
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->with(['category', 'staffs'])
                ->withCount('pageViews')
                ->orderBy('page_views_count', 'desc')
                ->take(5)
                ->get();
        });

        $editorPicks = Cache::remember('home_editor_picks', $cacheTtl, function () use ($leadStory) {
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->where('is_editor_pick', true)
                ->where('id', '!=', $leadStory?->id)
                ->with(['staffs'])
                ->latest('published_at')
                ->take(4)
                ->get();
        });

        $popularTags = Cache::remember('home_popular_tags', $cacheTtl, function () {
            return ArticleTag::select('tag', DB::raw('COUNT(*) as count'))
                ->groupBy('tag')
                ->orderByDesc('count')
                ->take(12)
                ->get();
        });

        $latest = Cache::remember('home_latest', $cacheTtl, function () {
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->with(['category', 'staffs'])
                ->latest('published_at')
                ->take(8)
                ->get();
        });

        $facebookUrl = Cache::remember('social_facebook_url', 86400, function () {
            return Setting::get('social_facebook', 'https://www.facebook.com/EducationTimesBD');
        });

        $videos = Cache::remember('home_videos', $cacheTtl, function () {
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->whereNotNull('video_url')
                ->with(['category', 'staffs'])
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        $worldArticles = Cache::remember('home_world_articles', $cacheTtl, function () {
            $cat = Category::where('slug', 'international')->first();
            if (!$cat) return collect();
            return Article::where('status', ArticleStatus::PUBLISHED->value)
                ->where('category_id', $cat->id)
                ->with(['category', 'staffs'])
                ->latest('published_at')
                ->take(5)
                ->get();
        });

        $breakingLabel = Setting::get('hero_breaking_label', 'ব্রেকিং নিউজ');
        $newsletterTitle = Setting::get('newsletter_title', 'নিউজলেটারে যোগ দিন');
        $newsletterPitch = Setting::get('newsletter_pitch', 'সর্বশেষ শিক্ষা সংবাদ সরাসরি আপনার ইমেইলে পান।');

        return view('home', compact(
            'categories', 'leadStory', 'featuredStories',
            'breakingStories', 'mostRead', 'editorPicks',
            'sliderArticles', 'facebookUrl', 'popularTags', 'latest',
            'videos', 'worldArticles',
            'breakingLabel', 'newsletterTitle', 'newsletterPitch'
        ));
    }
}
