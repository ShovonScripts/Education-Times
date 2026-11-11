<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->fulltext(['title_bn', 'title_en', 'excerpt_bn', 'body_bn'], 'articles_search_fulltext');
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropFulltext('articles_search_fulltext');
        });
    }
};
