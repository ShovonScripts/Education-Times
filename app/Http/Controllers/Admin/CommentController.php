<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with(['user', 'article'])
            ->latest()
            ->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);
        return back()->with('success', 'Comment approved.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rejected']);
        return back()->with('success', 'Comment rejected.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }
}
