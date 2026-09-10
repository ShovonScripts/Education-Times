<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Enums\ArticleStatus;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $videos = Article::where('status', ArticleStatus::PUBLISHED->value)
            ->whereNotNull('video_url')
            ->with(['category', 'staffs'])
            ->latest('published_at')
            ->paginate(24);

        return view('videos.index', compact('videos'));
    }
}
