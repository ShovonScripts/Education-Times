<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\SavedArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function toggle(Request $request, Article $article): JsonResponse
    {
        $user = $request->user();

        $existing = SavedArticle::where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            SavedArticle::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            $saved = true;
        }

        return response()->json(['saved' => $saved]);
    }
}
