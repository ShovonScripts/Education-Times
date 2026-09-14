<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PostController extends Controller
{
    use ClearsHomepageCache;

    public function index(Request $request): View
    {
        $query = Article::with(['author', 'category', 'staffs'])
            ->withSum('viewCounts', 'views');

        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('author_id'), fn($q) => $q->where('author_id', $request->author_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q) use ($s) {
                    $q->where('title_bn', 'like', "%{$s}%")
                        ->orWhere('title_en', 'like', "%{$s}%")
                        ->orWhere('excerpt_bn', 'like', "%{$s}%");
                });
            })
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to));

        $sortField = $request->sort ?? 'created_at';
        $sortDir = $request->dir === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['created_at', 'published_at', 'title_bn', 'slider_order'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->latest();
        }

        $articles = $query->paginate(25)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $authors = User::whereHas('articles')->select('id', 'name')->get();

        $counts = [
            'all' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'submitted' => Article::where('status', 'submitted')->count(),
            'draft' => Article::where('status', 'draft')->count(),
            'scheduled' => Article::where('status', 'scheduled')->count(),
        ];

        return view('admin.posts.index', compact('articles', 'categories', 'authors', 'counts'));
    }

    public function pending(): View
    {
        $articles = Article::where('status', 'submitted')
            ->with(['author', 'category', 'staffs'])
            ->latest()
            ->paginate(25);

        $counts = ['submitted' => Article::where('status', 'submitted')->count()];

        return view('admin.posts.pending', compact('articles', 'counts'));
    }

    public function slider(): View
    {
        $articles = Article::where('is_slider', true)
            ->where('status', 'published')
            ->with(['category'])
            ->orderBy('slider_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $available = Article::where('status', 'published')
            ->where('is_slider', false)
            ->with(['category'])
            ->latest('published_at')
            ->take(30)
            ->get();

        return view('admin.posts.slider', compact('articles', 'available'));
    }

    public function breaking(): View
    {
        $articles = Article::where('is_breaking', true)
            ->where('status', 'published')
            ->with(['category'])
            ->latest('published_at')
            ->paginate(25);

        return view('admin.posts.breaking', compact('articles'));
    }

    public function featured(): View
    {
        $articles = Article::where('is_featured', true)
            ->where('status', 'published')
            ->with(['category'])
            ->latest('published_at')
            ->paginate(25);

        return view('admin.posts.featured', compact('articles'));
    }

    public function scheduled(): View
    {
        $articles = Article::where('status', 'scheduled')
            ->with(['author', 'category'])
            ->orderBy('published_at')
            ->paginate(25);

        $overdue = Article::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->count();

        return view('admin.posts.scheduled', compact('articles', 'overdue'));
    }

    public function toggleFlag(Request $request, Article $article): JsonResponse|RedirectResponse
    {
        $request->validate(['flag' => 'required|in:is_breaking,is_featured,is_slider,is_editor_pick']);

        $field = $request->flag;
        $article->update([$field => !$article->$field]);

        if ($field === 'is_slider' && $article->is_slider) {
            $maxOrder = Article::where('is_slider', true)->max('slider_order') ?? 0;
            $article->update(['slider_order' => $maxOrder + 1]);
        }

        $this->clearHomepageCache();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'state' => $article->$field,
                'label' => $field === 'is_breaking' ? 'Breaking' : ($field === 'is_featured' ? 'Featured' : ($field === 'is_slider' ? 'Slider' : 'এডিটরস পিক')),
            ]);
        }

        return back()->with('success', 'ফ্লাগ আপডেট হয়েছে!');
    }

    public function updateStatus(Request $request, Article $article): RedirectResponse
    {
        $request->validate(['status' => 'required|in:draft,published,submitted,archived']);

        $data = ['status' => $request->status];
        if ($request->status === 'published' && !$article->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);

        $this->clearHomepageCache();

        $labels = ['published' => 'Published', 'draft' => 'Draft', 'submitted' => 'Pending Review', 'archived' => 'আর্কাইভ'];
        return back()->with('success', "অবস্থা '{$labels[$request->status]}' এ পরিবর্তন করা হয়েছে!");
    }

    public function updateSliderOrder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:articles,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                Article::where('id', $item['id'])->update(['slider_order' => $item['order']]);
            }
        });

        $this->clearHomepageCache();

        return response()->json(['success' => true]);
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
            'action' => 'required|in:publish,draft,archive,delete,breaking,featured,slider,unbreaking,unfeatured,unslider',
        ]);

        $articles = Article::whereIn('id', $request->ids);

        match ($request->action) {
            'publish' => $articles->update(['status' => 'published', 'published_at' => DB::raw('COALESCE(published_at, NOW())')]),
            'draft' => $articles->update(['status' => 'draft']),
            'archive' => $articles->update(['status' => 'archived']),
            'delete' => (function () use ($articles) {
                $articles->get()->each(fn ($article) => $article->deleteStoredImages());
                $articles->forceDelete();
            })(),
            'breaking' => $articles->update(['is_breaking' => true]),
            'featured' => $articles->update(['is_featured' => true]),
            'slider' => $articles->update(['is_slider' => true]),
            'unbreaking' => $articles->update(['is_breaking' => false]),
            'unfeatured' => $articles->update(['is_featured' => false]),
            'unslider' => $articles->update(['is_slider' => false]),
            default => null,
        };

        $this->clearHomepageCache();

        return back()->with('success', 'বাল্ক কার্যক্রম সম্পন্ন হয়েছে!');
    }
}
