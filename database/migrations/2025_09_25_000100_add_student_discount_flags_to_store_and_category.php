<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stores') && !Schema::hasColumn('stores', 'student_discount')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->boolean('student_discount')->default(false)->after('show_trending');
            });
        }

        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'student_discount')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('student_discount')->default(false)->after('recommended');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stores') && Schema::hasColumn('stores', 'student_discount')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('student_discount');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'student_discount')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('student_discount');
            });
        }
    }
};


