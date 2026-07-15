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
        // Use raw SQL to alter columns to nullable
        $columns = ['heading', 'content', 'stat1_number', 'stat1_label', 'stat2_number', 'stat2_label', 'stat3_number', 'stat3_label'];
        
        foreach ($columns as $column) {
            if (Schema::hasColumn('sliders', $column)) {
                $columnType = $this->getColumnType($column);
                DB::statement("ALTER TABLE sliders MODIFY COLUMN `{$column}` {$columnType} NULL");
            }
        }
    }
    
    private function getColumnType($column): string
    {
        if (in_array($column, ['heading', 'stat1_number', 'stat1_label', 'stat2_number', 'stat2_label', 'stat3_number', 'stat3_label'])) {
            return 'VARCHAR(255)';
        }
        if ($column === 'content') {
            return 'TEXT';
        }
        return 'VARCHAR(255)';
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            //
        });
    }
};
