<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'body' => 'required|string|max:2000',
            'parent_id' => ['nullable', Rule::exists('comments', 'id')->where('article_id', $request->article_id)],
        ]);

        Comment::create([
            'article_id' => $request->article_id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার মন্তব্য অনুমোদনের জন্য পাঠানো হয়েছে।');
    }
}
