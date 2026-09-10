<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\PageView;
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

        PageView::create([
            'viewable_type' => Article::class,
            'viewable_id' => $article->id,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'referer' => Request::header('referer'),
            'user_id' => auth()->id(),
            'created_at' => now(),
        ]);

        return view('article.show', compact('article', 'related'));
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
