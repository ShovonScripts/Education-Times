<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->index(['status', 'published_at'], 'articles_status_published_at_index');
            $table->index(['status', 'category_id', 'published_at'], 'articles_status_category_published_at_index');
            $table->index(['status', 'is_featured', 'published_at'], 'articles_status_featured_published_at_index');
            $table->index(['status', 'is_breaking', 'published_at'], 'articles_status_breaking_published_at_index');
            $table->index(['status', 'is_slider', 'slider_order'], 'articles_status_slider_order_index');
            $table->index('deleted_at', 'articles_deleted_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['article_id', 'status', 'parent_id'], 'comments_article_status_parent_index');
        });

        Schema::table('page_views', function (Blueprint $table) {
            $table->index('created_at', 'page_views_created_at_index');
        });

        Schema::table('article_tag', function (Blueprint $table) {
            $table->index('tag', 'article_tag_tag_index');
            $table->unique(['article_id', 'tag'], 'article_tag_article_id_tag_unique');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_status_published_at_index');
            $table->dropIndex('articles_status_category_published_at_index');
            $table->dropIndex('articles_status_featured_published_at_index');
            $table->dropIndex('articles_status_breaking_published_at_index');
            $table->dropIndex('articles_status_slider_order_index');
            $table->dropIndex('articles_deleted_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_article_status_parent_index');
        });

        Schema::table('page_views', function (Blueprint $table) {
            $table->dropIndex('page_views_created_at_index');
        });

        Schema::table('article_tag', function (Blueprint $table) {
            $table->dropUnique('article_tag_article_id_tag_unique');
            $table->dropIndex('article_tag_tag_index');
        });
    }
};
