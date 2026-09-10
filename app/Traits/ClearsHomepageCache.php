<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsHomepageCache
{
    private function clearHomepageCache(): void
    {
        $keys = [
            'home_categories',
            'home_lead_story',
            'home_featured_stories',
            'home_slider_articles',
            'home_breaking_stories',
            'home_most_read',
            'home_editor_picks',
            'home_popular_tags',
            'home_latest',
            'home_videos',
            'home_world_articles',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
