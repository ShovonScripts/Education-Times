<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id', 'category_id', 'district_id',
        'title_bn', 'title_en', 'slug',
        'excerpt_bn', 'body_bn',
        'featured_image', 'video_url', 'featured_image_caption', 'photo_credit',
        'meta_title', 'meta_description', 'focus_keywords', 'og_image', 'canonical_url',
        'status', 'is_breaking', 'is_featured', 'is_editor_pick', 'is_slider', 'slider_order',
        'reading_time_minutes', 'published_at', 'indexable',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_breaking' => 'boolean',
            'is_featured' => 'boolean',
            'is_editor_pick' => 'boolean',
            'is_slider' => 'boolean',
            'indexable' => 'boolean',
            'status' => \App\Enums\ArticleStatus::class,
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function staffs(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'article_staff');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ArticleTag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function pageViews(): MorphMany
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    public function viewCounts(): HasMany
    {
        return $this->hasMany(ArticleViewCount::class);
    }

    public function getViewCountAttribute(): int
    {
        return $this->viewCounts()->sum('views');
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'article_likes')
            ->withTimestamps();
    }

    public function getYouTubeEmbedAttribute(): ?string
    {
        if (!$this->video_url) return null;
        $id = null;
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $m)) {
            $id = $m[1];
        }
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    public function getYouTubeThumbnailAttribute(): ?string
    {
        if (!$this->video_url) return null;
        $id = null;
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $m)) {
            $id = $m[1];
        }
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public function getHasVideoAttribute(): bool
    {
        return $this->youTubeEmbed !== null;
    }

    /**
     * Resolve the featured image to a browser-ready URL.
     * Absolute URLs pass through as-is; local paths resolve via the public disk.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->resolveLocalImage($this->featured_image);
    }

    public function getOgImageUrlAttribute(): ?string
    {
        return $this->resolveLocalImage($this->og_image);
    }

    private function resolveLocalImage(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        if (preg_match('#^(https?:)?//|data:#i', (string) $value)) {
            return (string) $value;
        }
        return Storage::disk('public')->url((string) $value);
    }

    /**
     * Delete locally-stored image files that belong to this article.
     * Remote URLs are skipped。
     */
    public function deleteStoredImages(): void
    {
        foreach (['featured_image', 'og_image'] as $field) {
            $value = $this->{$field} ?? null;
            if (empty($value)) {
                continue;
            }
            if (preg_match('#^(https?:)?//|data:#i', (string) $value)) {
                continue;
            }
            Storage::disk('public')->delete((string) $value);
        }
    }
}
