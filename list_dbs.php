<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- DATABASES ---\n";
try {
    $dbs = DB::select('SHOW DATABASES');
    foreach ($dbs as $db) {
        echo $db->Database . "\n";
    }
} catch (\Exception $e) {
    echo "Error listing databases: " . $e->getMessage() . "\n";
}

echo "\n--- TABLES IN socialof_main ---\n";
try {
    // Check if socialof_main exists first
    $found = false;
    foreach ($dbs as $db) {
        if ($db->Database === 'socialof_main') {
            $found = true;
            break;
        }
    }

    if ($found) {
        $tables = DB::connection('mysql')->select('SHOW TABLES FROM socialof_main');
        foreach ($tables as $table) {
            $tableArray = (array)$table;
            echo array_values($tableArray)[0] . "\n";
        }
    } else {
        echo "Database 'socialof_main' not found.\n";
    }

} catch (\Exception $e) {
    echo "Error listing tables: " . $e->getMessage() . "\n";
}
