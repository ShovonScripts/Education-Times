<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Support\Str;

class ArticleService
{
    public function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = !empty($title) ? Str::slug($title) : '';
        if (empty($slug)) {
            $slug = 'article-' . Str::random(8);
            return $slug;
        }

        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        $query = Article::where('slug', $slug);
        if (!is_null($ignoreId)) {
            $query->where('id', '!=', $ignoreId);
        }
        return $query->exists();
    }

    public function calculateReadingTime(string $html): int
    {
        $text = strip_tags($html);
        $words = preg_split('/\s+/u', trim($text));
        $wordCount = count($words);
        $minutes = (int) ceil($wordCount / 200);
        return max(1, $minutes);
    }
}
