<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_view_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->unique(['article_id', 'date'], 'article_view_counts_article_id_date_unique');
            $table->index('date', 'article_view_counts_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_view_counts');
    }
};
