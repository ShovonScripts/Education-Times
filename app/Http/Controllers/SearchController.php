<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $districts = District::orderBy('name_bn')->get();

        $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $query = Article::where('status', 'published');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereFullText(['title_bn', 'title_en', 'excerpt_bn', 'body_bn'], $search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }

        if ($request->filled('from')) {
            $query->whereDate('published_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('published_at', '<=', $request->to);
        }

        $articles = $query->with(['category', 'author', 'staffs'])
            ->latest('published_at')
            ->paginate(12);

        $articles->appends($request->all());

        return view('search.index', compact('articles', 'categories', 'districts'));
    }
}
