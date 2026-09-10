<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Category;
use App\Enums\ArticleStatus;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffArticleController extends Controller
{
    public function __construct(private ArticleService $articleService) {}
    public function index(): View
    {
        $articles = Article::where('author_id', Auth::id())
            ->with(['category', 'staff', 'staffs'])
            ->latest()
            ->paginate(20);
        return view('staff.articles.index', compact('articles'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        return view('staff.articles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body_bn' => 'required|string',
            'excerpt_bn' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'video_url' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
        ]);

        $validated['slug'] = $this->articleService->generateUniqueSlug($validated['title_bn']);
        $validated['author_id'] = Auth::id();
        $validated['status'] = ArticleStatus::DRAFT->value;
        $validated['reading_time_minutes'] = $this->articleService->calculateReadingTime($validated['body_bn']);

        unset($validated['featured_image']);

        $article = Article::create($validated);

        if ($request->hasFile('featured_image')) {
            $article->update(['featured_image' => $request->file('featured_image')->store('articles', 'public')]);
        }

        if (!empty($validated['tags'])) {
            $tags = explode(',', $validated['tags']);
            foreach ($tags as $tag) {
                ArticleTag::create([
                    'article_id' => $article->id,
                    'tag' => trim($tag),
                ]);
            }
        }

        return redirect()->route('staff.articles.index')
            ->with('success', 'খসড়া সংরক্ষিত হয়েছে!');
    }

    public function edit(Article $article): View
    {
        if ($article->author_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        return view('staff.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        if ($article->author_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body_bn' => 'required|string',
            'excerpt_bn' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'video_url' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'action' => 'required|in:save,submit',
        ]);

        $featuredImage = $article->featured_image;

        if ($request->hasFile('featured_image')) {
            $article->deleteStoredImages();
            $featuredImage = $request->file('featured_image')->store('articles', 'public');
        } elseif ($request->boolean('remove_featured_image')) {
            $article->deleteStoredImages();
            $featuredImage = null;
        }

        unset($validated['featured_image']);

        $article->update([
            'title_bn' => $validated['title_bn'],
            'category_id' => $validated['category_id'],
            'body_bn' => $validated['body_bn'],
            'excerpt_bn' => $validated['excerpt_bn'] ?? null,
            'featured_image' => $featuredImage,
            'video_url' => $validated['video_url'] ?? null,
            'status' => $validated['action'] === 'submit' ? ArticleStatus::SUBMITTED->value : ArticleStatus::DRAFT->value,
            'reading_time_minutes' => $this->articleService->calculateReadingTime($validated['body_bn']),
        ]);

        if (isset($validated['tags'])) {
            $article->tags()->delete();
            if (!empty($validated['tags'])) {
                $tags = explode(',', $validated['tags']);
                foreach ($tags as $tag) {
                    ArticleTag::create([
                        'article_id' => $article->id,
                        'tag' => trim($tag),
                    ]);
                }
            }
        }

        $msg = $validated['action'] === 'submit'
            ? 'আর্টিকেল পর্যালোচনার জন্য জমা দেওয়া হয়েছে!'
            : 'খসড়া আপডেট করা হয়েছে!';

        return redirect()->route('staff.articles.index')->with('success', $msg);
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->author_id !== Auth::id()) {
            abort(403);
        }
        $article->deleteStoredImages();
        $article->forceDelete();
        return redirect()->route('staff.articles.index')->with('success', 'আর্টিকেল ডিলিট করা হয়েছে!');
    }
}
