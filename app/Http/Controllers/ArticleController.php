<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleViewCount;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show($slug): View
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->with(['author', 'category', 'district', 'staffs', 'tags'])
            ->firstOrFail();

        $related = Article::where('status', 'published')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with(['author', 'staffs'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $comments = Comment::where('article_id', $article->id)
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with('user', 'replies.user')
            ->latest()
            ->get();

        $botPatterns = ['Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider', 'YandexBot', 'AhrefsBot', 'SemrushBot', 'MJ12bot', 'facebookexternalhit', 'Twitterbot', 'LinkedInBot', 'WhatsApp', 'curl', 'wget', 'python', 'requests', 'spider', 'crawler'];
        $userAgent = Request::userAgent() ?? '';
        $isBot = false;
        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                $isBot = true;
                break;
            }
        }

        if (!$isBot) {
            ArticleViewCount::upsert(
                [
                    'article_id' => $article->id,
                    'date' => now()->toDateString(),
                    'views' => 1,
                ],
                ['article_id', 'date'],
                ['views' => new \Illuminate\Database\Query\Expression('views + 1')]
            );
        }

        // Like/save state for the current user (avoid N+1 on two small queries)
        $liked = false;
        $saved = false;
        if ($user = auth()->user()) {
            $liked = $user->likedArticles()->where('article_id', $article->id)->exists();
            $saved = $user->savedArticles()->where('article_id', $article->id)->exists();
        }

        return view('article.show', compact('article', 'related', 'comments', 'liked', 'saved'));
    }

    public function category($slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $articles = Article::where('status', 'published')
            ->where('category_id', $category->id)
            ->with(['author', 'category', 'staffs'])
            ->latest('published_at')
            ->paginate(12);

        return view('article.category', compact('category', 'articles'));
    }
}
