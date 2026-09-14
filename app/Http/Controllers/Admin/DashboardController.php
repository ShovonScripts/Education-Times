<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleViewCount;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();
        $now = now();

        $stats = [
            'articles' => Article::count(),
            'users' => User::count(),
            'comments' => Comment::count(),
            'today_published' => Article::where('status', 'published')->where('published_at', '>=', $today)->count(),
            'drafts' => Article::where('status', 'draft')->count(),
            'scheduled' => Article::where('status', 'scheduled')->count(),
            'submitted' => Article::where('status', 'submitted')->count(),
            'breaking' => Article::where('is_breaking', true)->where('status', 'published')->count(),
            'featured' => Article::where('is_featured', true)->where('status', 'published')->count(),
        ];

        $recentArticles = Article::with(['author', 'category', 'staffs'])
            ->latest()
            ->take(10)
            ->get();

        $articleIds = Article::where('status', 'published')
            ->latest('published_at')
            ->take(10)
            ->pluck('id');

        $viewCounts = ArticleViewCount::whereIn('article_id', $articleIds)
            ->select('article_id', DB::raw('sum(views) as total'))
            ->groupBy('article_id')
            ->pluck('total', 'article_id');

        $topViewed = Article::whereIn('id', $articleIds)
            ->with(['category'])
            ->get()
            ->map(function ($article) use ($viewCounts) {
                $article->view_count = $viewCounts[$article->id] ?? 0;
                return $article;
            })
            ->sortByDesc('view_count')
            ->take(10);

        $recentComments = Comment::with(['user', 'article'])
            ->latest()
            ->take(8)
            ->get();

        $articlesByCategory = Article::where('status', 'published')
            ->select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $todayViews = ArticleViewCount::where('date', '>=', $today->toDateString())->sum('views');

        return view('admin.dashboard', compact(
            'stats', 'recentArticles', 'topViewed', 'recentComments', 'articlesByCategory', 'todayViews'
        ));
    }
}
