<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('store_logo');
            }
            if (!Schema::hasColumn('stores', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('stores', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('stores', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('instagram_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'youtube_url')) {
                $table->dropColumn('youtube_url');
            }
            if (Schema::hasColumn('stores', 'instagram_url')) {
                $table->dropColumn('instagram_url');
            }
            if (Schema::hasColumn('stores', 'twitter_url')) {
                $table->dropColumn('twitter_url');
            }
            if (Schema::hasColumn('stores', 'facebook_url')) {
                $table->dropColumn('facebook_url');
            }
        });
    }
};
