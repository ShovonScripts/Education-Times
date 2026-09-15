<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

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

    private function processImage(UploadedFile $image): string
    {
        $img = Image::read($image);
        $img->scaleDown(width: 1200);
        $filename = uniqid() . '.webp';
        $path = 'articles/' . $filename;

        // Storage::put() creates missing directories automatically — unlike
        // Intervention's save(), which fails when articles/ doesn't exist yet.
        \Illuminate\Support\Facades\Storage::disk('public')
            ->put($path, (string) $img->toWebp(82));

        return $path;
    }

    public function create(array $data, ?UploadedFile $image = null): Article
    {
        $article = Article::create($data);

        if ($image) {
            $article->update(['featured_image' => $this->processImage($image)]);
        }

        if (!empty($data['tags'])) {
            $tags = explode(',', $data['tags']);
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if ($tag !== '') {
                    ArticleTag::create([
                        'article_id' => $article->id,
                        'tag' => $tag,
                    ]);
                }
            }
        }

        return $article;
    }

    public function update(Article $article, array $data, ?UploadedFile $image = null): Article
    {
        if ($image) {
            $article->deleteStoredImages();
            $data['featured_image'] = $this->processImage($image);
        } elseif (isset($data['remove_featured_image']) && $data['remove_featured_image']) {
            $article->deleteStoredImages();
            $data['featured_image'] = null;
        }

        $article->update($data);

        if (array_key_exists('tags', $data)) {
            $article->tags()->delete();
            if (!empty($data['tags'])) {
                $tags = explode(',', $data['tags']);
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if ($tag !== '') {
                        ArticleTag::create([
                            'article_id' => $article->id,
                            'tag' => $tag,
                        ]);
                    }
                }
            }
        }

        return $article;
    }
}
