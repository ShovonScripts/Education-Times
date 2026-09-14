<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use function Clean;

class DashboardController extends Controller
{
    public function __construct(private ArticleService $articleService) {}

    public function index(): View
    {
        $user = Auth::user()->load('district');

        $savedArticles = $user->savedArticles()
            ->with('article')
            ->latest()
            ->take(5)
            ->get();

        $comments = $user->comments()
            ->with('article')
            ->latest()
            ->take(5)
            ->get();

        $articles = collect();
        $totalPosts = 0;
        $publishedPosts = 0;
        $pendingPosts = 0;
        $draftPosts = 0;
        $categories = collect();

        if ($user->is_editor) {
            $articles = Article::where('author_id', $user->id)
                ->with(['category'])
                ->latest()
                ->paginate(10);

            $totalPosts = Article::where('author_id', $user->id)->count();
            $publishedPosts = Article::where('author_id', $user->id)->where('status', 'published')->count();
            $pendingPosts = Article::where('author_id', $user->id)->where('status', 'submitted')->count();
            $draftPosts = Article::where('author_id', $user->id)->where('status', 'draft')->count();
            $categories = Category::where('is_active', true)->orderBy('order')->get();
        }

        return view('dashboard.index', compact(
            'user', 'savedArticles', 'comments',
            'articles', 'totalPosts', 'publishedPosts',
            'pendingPosts', 'draftPosts', 'categories'
        ));
    }

    public function storePost(Request $request): RedirectResponse
    {
        if (!Auth::user()->is_editor) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body_bn' => 'required|string',
            'excerpt_bn' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'tags' => 'nullable|string',
        ]);

        $validated['slug'] = $this->articleService->generateUniqueSlug($validated['title_bn']);
        $validated['author_id'] = Auth::id();
        $validated['status'] = 'submitted';
        $validated['body_bn'] = clean($validated['body_bn']);
        $validated['reading_time_minutes'] = $this->articleService->calculateReadingTime($validated['body_bn']);

        $article = $this->articleService->create($validated, $request->file('featured_image'));

        return redirect()->route('dashboard')
            ->with('success', 'আপনার পোস্ট জমা দেওয়া হয়েছে! পর্যালোচনার পর তা প্রকাশ করা হবে।');
    }
}
