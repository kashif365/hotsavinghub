<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verification_tags', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->enum('type', ['meta', 'script', 'custom'])->default('meta');
            $table->string('attribute_key')->nullable();
            $table->string('attribute_value')->nullable();
            $table->text('content')->nullable();
            $table->longText('code')->nullable();
            $table->string('script_attributes')->nullable();
            $table->enum('placement', ['head_start', 'head_end', 'body_start', 'body_end'])->default('head_end');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed existing verification tags & scripts so nothing breaks after migration
        DB::table('verification_tags')->insert([
            [
                'label' => 'FO Verify',
                'type' => 'meta',
                'attribute_key' => 'name',
                'attribute_value' => 'fo-verify',
                'content' => 'b396d87c-3384-48bd-a28d-105f764a03eb',
                'code' => null,
                'script_attributes' => null,
                'placement' => 'head_start',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Admitad Verify',
                'type' => 'meta',
                'attribute_key' => 'name',
                'attribute_value' => 'verify-admitad',
                'content' => 'fc2c5284bd',
                'code' => null,
                'script_attributes' => null,
                'placement' => 'head_start',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Google Site Verification',
                'type' => 'meta',
                'attribute_key' => 'name',
                'attribute_value' => 'google-site-verification',
                'content' => '180x7BCqhq-QlvmhW7qDf04cvDmCHvurC7NpX-_8cJU',
                'code' => null,
                'script_attributes' => null,
                'placement' => 'head_end',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Google Tag Inline Config',
                'type' => 'script',
                'attribute_key' => null,
                'attribute_value' => null,
                'content' => null,
                'code' => "window.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', 'G-V2RZW7098F');",
                'script_attributes' => null,
                'placement' => 'head_end',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Google Analytics Loader',
                'type' => 'custom',
                'attribute_key' => null,
                'attribute_value' => null,
                'content' => null,
                'code' => '<script async src="https://www.googletagmanager.com/gtag/js?id=G-N1NEEERT7F"></script>',
                'script_attributes' => null,
                'placement' => 'head_end',
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Google Analytics Config',
                'type' => 'script',
                'attribute_key' => null,
                'attribute_value' => null,
                'content' => null,
                'code' => "window.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', 'G-N1NEEERT7F');",
                'script_attributes' => null,
                'placement' => 'head_end',
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Google Tag Manager (body)',
                'type' => 'script',
                'attribute_key' => null,
                'attribute_value' => null,
                'content' => null,
                'code' => "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\nnew Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\nj=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n})(window,document,'script','dataLayer','GTM-MSCDVSDC');",
                'script_attributes' => null,
                'placement' => 'body_end',
                'sort_order' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_tags');
    }
};

