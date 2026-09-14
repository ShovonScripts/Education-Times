<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Redirect;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function dashboard(): View
    {
        $total = Article::count();
        $published = Article::where('status', 'published')->count();
        $noMetaTitle = Article::where(function ($q) {
            $q->whereNull('meta_title')->orWhere('meta_title', '');
        })->count();
        $noMetaDesc = Article::where(function ($q) {
            $q->whereNull('meta_description')->orWhere('meta_description', '');
        })->count();
        $noOgImage = Article::where(function ($q) {
            $q->whereNull('og_image')->orWhere('og_image', '');
        })->count();
        $noFocusKw = Article::where(function ($q) {
            $q->whereNull('focus_keywords')->orWhere('focus_keywords', '');
        })->count();
        $notIndexable = Article::where('indexable', false)->count();
        $shortTitle = Article::where('status', 'published')
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNull('meta_title')->orWhere('meta_title', '');
                })->orWhereRaw('LENGTH(meta_title) < 30');
            })
            ->count();
        $shortDesc = Article::where('status', 'published')
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNull('meta_description')->orWhere('meta_description', '');
                })->orWhereRaw('LENGTH(meta_description) < 50');
            })
            ->count();
        $redirects = Redirect::count();
        $activeRedirects = Redirect::where('is_active', true)->count();

        $issues = [
            ['label' => 'মেটা টাইটেল নেই', 'count' => $noMetaTitle, 'icon' => 'meta-title', 'severity' => 'high'],
            ['label' => 'মেটা ডেসক্রিপশন নেই', 'count' => $noMetaDesc, 'icon' => 'meta-desc', 'severity' => 'high'],
            ['label' => 'OG ইমেজ নেই', 'count' => $noOgImage, 'icon' => 'og-image', 'severity' => 'medium'],
            ['label' => 'ফোকাস Keywords নেই', 'count' => $noFocusKw, 'icon' => 'keywords', 'severity' => 'medium'],
            ['label' => 'ইন্ডেক্স করা যাচ্ছে No', 'count' => $notIndexable, 'icon' => 'no-index', 'severity' => 'low'],
            ['label' => 'শর্ট meta_title (<30 chars)', 'count' => $shortTitle, 'icon' => 'short-title', 'severity' => 'medium'],
            ['label' => 'শর্ট meta_desc (<50 chars)', 'count' => $shortDesc, 'icon' => 'short-desc', 'severity' => 'medium'],
        ];

        $score = $total > 0 ? round((1 - ($noMetaTitle + $noMetaDesc + $noOgImage + $noFocusKw) / ($total * 4)) * 100) : 100;

        return view('admin.seo.dashboard', compact(
            'total', 'published', 'issues', 'score', 'redirects', 'activeRedirects'
        ));
    }

    public function bulkEditor(Request $request): View
    {
        $query = Article::with(['category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title_bn', 'like', "%{$s}%")
                    ->orWhere('title_en', 'like', "%{$s}%");
            });
        }
        if ($request->filled('seo_issue')) {
            match ($request->seo_issue) {
                'no_meta_title' => $query->whereNull('meta_title')->orWhere('meta_title', ''),
                'no_meta_desc' => $query->whereNull('meta_description')->orWhere('meta_description', ''),
                'no_og_image' => $query->whereNull('og_image')->orWhere('og_image', ''),
                'no_keywords' => $query->whereNull('focus_keywords')->orWhere('focus_keywords', ''),
                default => null,
            };
        }

        $articles = $query->latest()->paginate(30)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $total = Article::count();

        return view('admin.seo.bulk-editor', compact('articles', 'categories', 'total'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'articles' => 'required|array',
            'articles.*.id' => 'required|exists:articles,id',
            'articles.*.meta_title' => 'nullable|string|max:255',
            'articles.*.meta_description' => 'nullable|string|max:500',
            'articles.*.og_image' => 'nullable|string|max:500',
            'articles.*.focus_keywords' => 'nullable|string|max:255',
            'articles.*.indexable' => 'nullable|boolean',
        ]);

        foreach ($request->articles as $data) {
            Article::where('id', $data['id'])->update([
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'og_image' => $data['og_image'] ?? null,
                'focus_keywords' => $data['focus_keywords'] ?? null,
                'indexable' => filter_var($data['indexable'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        return back()->with('success', count($request->articles) . ' টি আর্টিকেলের SEO আপডেট হয়েছে!');
    }

    public function sitemap(): \Illuminate\Http\Response
    {
        $xml = Cache::remember('sitemap_xml', 3600, function () {
            $urls = ['<?xml version="1.0" encoding="UTF-8"?>'];
            $urls[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';
            $urls[] = '<url><loc>' . url('/') . '</loc><priority>1.0</priority><changefreq>hourly</changefreq></url>';

            $categories = Category::where('is_active', true)->get(['slug']);
            foreach ($categories as $cat) {
                $urls[] = '<url><loc>' . url('/category/' . $cat->slug) . '</loc><priority>0.8</priority><changefreq>daily</changefreq></url>';
            }

            Article::where('status', 'published')
                ->where('indexable', true)
                ->whereNotNull('published_at')
                ->select('slug', 'title_bn', 'published_at', 'updated_at')
                ->orderByDesc('published_at')
                ->chunk(1000, function ($articles) use (&$urls) {
                    foreach ($articles as $article) {
                        $urls[] = '<url>';
                        $urls[] = '<loc>' . url('/news/' . $article->slug) . '</loc>';
                        $urls[] = '<lastmod>' . $article->updated_at->toIso8601String() . '</lastmod>';
                        $urls[] = '<priority>0.9</priority>';
                        $urls[] = '<changefreq>daily</changefreq>';
                        $urls[] = '<news:news>';
                        $urls[] = '<news:publication_date>' . $article->published_at?->toIso8601String() . '</news:publication_date>';
                        $urls[] = '<news:title>' . htmlspecialchars($article->title_bn, ENT_XML1, 'UTF-8') . '</news:title>';
                        $urls[] = '</news:news>';
                        $urls[] = '</url>';
                    }
                });

            $urls[] = '</urlset>';

            return implode('', $urls);
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function robotsEditor(): View
    {
        $robots = Setting::get('robots_txt', "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml'));
        return view('admin.seo.robots', compact('robots'));
    }

    public function robotsUpdate(Request $request): RedirectResponse
    {
        $request->validate(['content' => 'required|string']);
        Setting::set('robots_txt', $request->content);
        return back()->with('success', 'robots.txt আপডেট হয়েছে!');
    }

    public function showRobotsTxt(): \Illuminate\Http\Response
    {
        $robots = Setting::get('robots_txt', "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml'));
        return response($robots)->header('Content-Type', 'text/plain');
    }

    public function redirects(): View
    {
        $redirects = Redirect::latest('hits')->paginate(25);
        return view('admin.seo.redirects', compact('redirects'));
    }

    public function redirectStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:500|unique:redirects',
            'new_url' => ['required', 'string', 'max:500', function ($attribute, $value, $fail) {
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    $host = parse_url($value, PHP_URL_HOST);
                    $allowedHost = parse_url(config('app.url'), PHP_URL_HOST);
                    if ($host && $host !== $allowedHost) {
                        $fail('External redirects are not allowed.');
                    }
                }
            }],
            'status_code' => 'required|in:301,302',
        ]);

        $validated['old_url'] = '/' . ltrim($validated['old_url'], '/');
        Redirect::create($validated);

        return redirect()->route('admin.seo.redirects')->with('success', 'Redirects তৈরি করা হয়েছে!');
    }

    public function redirectUpdate(Request $request, Redirect $redirect): RedirectResponse
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:500|unique:redirects,old_url,' . $redirect->id,
            'new_url' => ['required', 'string', 'max:500', function ($attribute, $value, $fail) {
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    $host = parse_url($value, PHP_URL_HOST);
                    $allowedHost = parse_url(config('app.url'), PHP_URL_HOST);
                    if ($host && $host !== $allowedHost) {
                        $fail('External redirects are not allowed.');
                    }
                }
            }],
            'status_code' => 'required|in:301,302',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['old_url'] = '/' . ltrim($validated['old_url'], '/');
        $validated['is_active'] = $request->boolean('is_active');
        $redirect->update($validated);

        return redirect()->route('admin.seo.redirects')->with('success', 'Redirects আপডেট হয়েছে!');
    }

    public function redirectDestroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();
        return redirect()->route('admin.seo.redirects')->with('success', 'Redirects ডিলিট করা হয়েছে!');
    }

    public function articleSeoAnalysis(Article $article): \Illuminate\Http\JsonResponse
    {
        $checks = [];

        $titleLen = mb_strlen($article->meta_title ?? $article->title_bn, 'UTF-8');
        $checks[] = [
            'label' => 'Meta Title',
            'value' => $article->meta_title ?? $article->title_bn,
            'status' => $titleLen >= 30 && $titleLen <= 60 ? 'pass' : ($titleLen < 30 ? 'warning' : 'fail'),
            'message' => $titleLen < 30 ? 'খুব ছোট ('.$titleLen.' chars, 30-60 প্রয়োজন)' : ($titleLen > 60 ? 'খুব বড় ('.$titleLen.' chars, 30-60 প্রয়োজন)' : 'পারফেক্ট ('.$titleLen.' chars)'),
        ];

        $descLen = mb_strlen($article->meta_description ?? '', 'UTF-8');
        $checks[] = [
            'label' => 'Meta Description',
            'value' => $article->meta_description ?? '—',
            'status' => $descLen >= 50 && $descLen <= 160 ? 'pass' : ($descLen === 0 ? 'fail' : 'warning'),
            'message' => $descLen === 0 ? 'সেট করা হয়নি' : ($descLen < 50 ? 'খুব ছোট ('.$descLen.' chars)' : ($descLen > 160 ? 'খুব বড় ('.$descLen.' chars)' : 'পারফেক্ট ('.$descLen.' chars)')),
        ];

        $checks[] = [
            'label' => 'OG Image',
            'value' => $article->og_image ?? $article->featured_image ?? '—',
            'status' => !empty($article->og_image ?? $article->featured_image) ? 'pass' : 'fail',
            'message' => empty($article->og_image ?? $article->featured_image) ? 'OG ইমেজ সেট করা হয়নি' : 'সেট করা আছে',
        ];

        $checks[] = [
            'label' => 'Focus Keywords',
            'value' => $article->focus_keywords ?? '—',
            'status' => !empty($article->focus_keywords) ? 'pass' : 'fail',
            'message' => empty($article->focus_keywords) ? 'ফোকাস Keywords সেট করা হয়নি' : 'সেট করা আছে',
        ];

        $kwInTitle = false;
        $kwInBody = false;
        $kwInSlug = false;
        if (!empty($article->focus_keywords)) {
            $keywords = explode(',', $article->focus_keywords);
            foreach ($keywords as $kw) {
                $kw = trim($kw);
                if (!empty($kw)) {
                    if (Str::contains($article->title_bn, $kw)) $kwInTitle = true;
                    if (Str::contains(strip_tags($article->body_bn ?? ''), $kw)) $kwInBody = true;
                    if (Str::contains($article->slug, Str::slug($kw))) $kwInSlug = true;
                }
            }
        }

        $checks[] = [
            'label' => 'Keyword in Title',
            'value' => $kwInTitle ? 'Yes' : 'No',
            'status' => $kwInTitle ? 'pass' : 'fail',
            'message' => $kwInTitle ? 'Keywords টাইটেলে আছে' : 'Keywords টাইটেলে নেই',
        ];

        $checks[] = [
            'label' => 'Keyword in Body',
            'value' => $kwInBody ? 'Yes' : 'No',
            'status' => $kwInBody ? 'pass' : 'fail',
            'message' => $kwInBody ? 'Keywords বডিতে আছে' : 'Keywords বডিতে নেই',
        ];

        $checks[] = [
            'label' => 'Indexable',
            'value' => $article->indexable ? 'Active' : 'Inactive',
            'status' => $article->indexable ? 'pass' : 'fail',
            'message' => $article->indexable ? 'সার্চ ইঞ্জিন ইন্ডেক্স করতে পারবে' : 'ইন্ডেক্স বন্ধ আছে',
        ];

        $passCount = count(array_filter($checks, fn($c) => $c['status'] === 'pass'));
        $score = count($checks) > 0 ? round(($passCount / count($checks)) * 100) : 0;

        return response()->json(['checks' => $checks, 'score' => $score]);
    }
}
