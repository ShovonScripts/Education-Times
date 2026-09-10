<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_staff', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->primary(['article_id', 'staff_id']);
        });

        // Migrate existing staff_id values into pivot
        Article::whereNotNull('staff_id')->each(function (Article $article) {
            DB::table('article_staff')->insert([
                'article_id' => $article->id,
                'staff_id' => $article->staff_id,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_staff');
    }
};
