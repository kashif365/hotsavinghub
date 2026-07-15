<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create test user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Seed categories first (needed for stores)
        $this->call(CategorySeeder::class);
        
        // Seed networks
        $this->call(NetworkSeeder::class);
        
        // Seed events
        $this->call(EventSeeder::class);
        
        // Seed stores
        $this->call(StoreSeeder::class);
        
        // Seed coupons
        $this->call(CouponSeeder::class);
        
        // Seed pages
        $this->call(PageSeeder::class);
        
        // Seed settings
        $this->call(SettingSeeder::class);
        $this->call(ColorSettingsSeeder::class);
    }
}
