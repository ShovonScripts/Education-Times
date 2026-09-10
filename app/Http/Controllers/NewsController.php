<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->get('category');
        $selectedCategory = null;

        $query = Article::where('status', 'published')
            ->with(['category', 'staffs'])
            ->latest('published_at');

        if ($categorySlug) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        $articles = $query->paginate(20)->withQueryString();

        // Group articles by date for timeline display
        $grouped = $articles->getCollection()->groupBy(function ($article) {
            return $article->published_at?->toDateString();
        });

        $categories = Category::whereHas('articles', fn($q) => $q->where('status', 'published'))
            ->orderBy('name_bn')
            ->get();

        return view('news.index', compact('articles', 'grouped', 'categories', 'selectedCategory'));
    }
}
