<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('stores', 'meta_keywords')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('stores', 'meta_keywords')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('meta_keywords');
            });
        }
    }
};
