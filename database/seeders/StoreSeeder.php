<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            // A
            ['store_name' => 'Amazon', 'seo_url' => 'amazon'],
            ['store_name' => 'ASOS', 'seo_url' => 'asos'],
            ['store_name' => 'Argos', 'seo_url' => 'argos'],
            ['store_name' => 'Apple', 'seo_url' => 'apple'],
            ['store_name' => 'Adidas', 'seo_url' => 'adidas'],
            
            // B
            ['store_name' => 'Boots', 'seo_url' => 'boots'],
            ['store_name' => 'Boden', 'seo_url' => 'boden'],
            ['store_name' => 'Boohoo', 'seo_url' => 'boohoo'],
            ['store_name' => 'Burton', 'seo_url' => 'burton'],
            ['store_name' => 'B&Q', 'seo_url' => 'bandq'],
            
            // C
            ['store_name' => 'Currys', 'seo_url' => 'currys'],
            ['store_name' => 'Clarks', 'seo_url' => 'clarks'],
            ['store_name' => 'Costa Coffee', 'seo_url' => 'costa-coffee'],
            ['store_name' => 'Carphone Warehouse', 'seo_url' => 'carphone-warehouse'],
            ['store_name' => 'Cath Kidston', 'seo_url' => 'cath-kidston'],
            
            // D
            ['store_name' => 'Debenhams', 'seo_url' => 'debenhams'],
            ['store_name' => 'Dunelm', 'seo_url' => 'dunelm'],
            ['store_name' => 'Dorothy Perkins', 'seo_url' => 'dorothy-perkins'],
            ['store_name' => 'Domino\'s Pizza', 'seo_url' => 'dominos-pizza'],
            ['store_name' => 'Diesel', 'seo_url' => 'diesel'],
            
            // E
            ['store_name' => 'eBay', 'seo_url' => 'ebay'],
            ['store_name' => 'Evans', 'seo_url' => 'evans'],
            ['store_name' => 'Ecco', 'seo_url' => 'ecco'],
            ['store_name' => 'Etsy', 'seo_url' => 'etsy'],
            ['store_name' => 'Expedia', 'seo_url' => 'expedia'],
            
            // F
            ['store_name' => 'Fashion Nova', 'seo_url' => 'fashion-nova'],
            ['store_name' => 'Forever 21', 'seo_url' => 'forever-21'],
            ['store_name' => 'Fossil', 'seo_url' => 'fossil'],
            ['store_name' => 'Farfetch', 'seo_url' => 'farfetch'],
            ['store_name' => 'Fiverr', 'seo_url' => 'fiverr'],
            
            // G
            ['store_name' => 'Gap', 'seo_url' => 'gap'],
            ['store_name' => 'Groupon', 'seo_url' => 'groupon'],
            ['store_name' => 'Gymshark', 'seo_url' => 'gymshark'],
            ['store_name' => 'GAME', 'seo_url' => 'game'],
            ['store_name' => 'Gant', 'seo_url' => 'gant'],
            
            // H
            ['store_name' => 'H&M', 'seo_url' => 'h-and-m'],
            ['store_name' => 'Harrods', 'seo_url' => 'harrods'],
            ['store_name' => 'Halfords', 'seo_url' => 'halfords'],
            ['store_name' => 'Holland & Barrett', 'seo_url' => 'holland-barrett'],
            ['store_name' => 'Hugo Boss', 'seo_url' => 'hugo-boss'],
            
            // I
            ['store_name' => 'IKEA', 'seo_url' => 'ikea'],
            ['store_name' => 'ITV', 'seo_url' => 'itv'],
            ['store_name' => 'Iceland', 'seo_url' => 'iceland'],
            ['store_name' => 'Interflora', 'seo_url' => 'interflora'],
            ['store_name' => 'Intu', 'seo_url' => 'intu'],
            
            // J
            ['store_name' => 'John Lewis', 'seo_url' => 'john-lewis'],
            ['store_name' => 'Just Eat', 'seo_url' => 'just-eat'],
            ['store_name' => 'JD Sports', 'seo_url' => 'jd-sports'],
            ['store_name' => 'Joules', 'seo_url' => 'joules'],
            ['store_name' => 'Jigsaw', 'seo_url' => 'jigsaw'],
            
            // K
            ['store_name' => 'KFC', 'seo_url' => 'kfc'],
            ['store_name' => 'Krispy Kreme', 'seo_url' => 'krispy-kreme'],
            ['store_name' => 'Kmart', 'seo_url' => 'kmart'],
            ['store_name' => 'Kiehl\'s', 'seo_url' => 'kiehls'],
            ['store_name' => 'Kickers', 'seo_url' => 'kickers'],
            
            // L
            ['store_name' => 'Lush', 'seo_url' => 'lush'],
            ['store_name' => 'Lacoste', 'seo_url' => 'lacoste'],
            ['store_name' => 'Levi\'s', 'seo_url' => 'levis'],
            ['store_name' => 'Lego', 'seo_url' => 'lego'],
            ['store_name' => 'Laura Ashley', 'seo_url' => 'laura-ashley'],
            
            // M
            ['store_name' => 'Marks & Spencer', 'seo_url' => 'marks-and-spencer'],
            ['store_name' => 'McDonald\'s', 'seo_url' => 'mcdonalds'],
            ['store_name' => 'Monsoon', 'seo_url' => 'monsoon'],
            ['store_name' => 'Missguided', 'seo_url' => 'missguided'],
            ['store_name' => 'Matalan', 'seo_url' => 'matalan'],
            
            // N
            ['store_name' => 'Next', 'seo_url' => 'next'],
            ['store_name' => 'Nike', 'seo_url' => 'nike'],
            ['store_name' => 'New Look', 'seo_url' => 'new-look'],
            ['store_name' => 'Netflix', 'seo_url' => 'netflix'],
            ['store_name' => 'Nando\'s', 'seo_url' => 'nandos'],
            
            // O
            ['store_name' => 'O2', 'seo_url' => 'o2'],
            ['store_name' => 'Office', 'seo_url' => 'office'],
            ['store_name' => 'Ocado', 'seo_url' => 'ocado'],
            ['store_name' => 'Oasis', 'seo_url' => 'oasis'],
            ['store_name' => 'Odeon', 'seo_url' => 'odeon'],
            
            // P
            ['store_name' => 'Primark', 'seo_url' => 'primark'],
            ['store_name' => 'Pizza Hut', 'seo_url' => 'pizza-hut'],
            ['store_name' => 'Pandora', 'seo_url' => 'pandora'],
            ['store_name' => 'Puma', 'seo_url' => 'puma'],
            ['store_name' => 'Pizza Express', 'seo_url' => 'pizza-express'],
            
            // Q
            ['store_name' => 'Qwertee', 'seo_url' => 'qwertee'],
            ['store_name' => 'Quiksilver', 'seo_url' => 'quiksilver'],
            ['store_name' => 'QVC', 'seo_url' => 'qvc'],
            ['store_name' => 'Quiz', 'seo_url' => 'quiz'],
            ['store_name' => 'Qantas', 'seo_url' => 'qantas'],
            
            // R
            ['store_name' => 'River Island', 'seo_url' => 'river-island'],
            ['store_name' => 'Reebok', 'seo_url' => 'reebok'],
            ['store_name' => 'Ralph Lauren', 'seo_url' => 'ralph-lauren'],
            ['store_name' => 'Ryman', 'seo_url' => 'ryman'],
            ['store_name' => 'Rip Curl', 'seo_url' => 'rip-curl'],
            
            // S
            ['store_name' => 'Samsung', 'seo_url' => 'samsung'],
            ['store_name' => 'Sports Direct', 'seo_url' => 'sports-direct'],
            ['store_name' => 'Screwfix', 'seo_url' => 'screwfix'],
            ['store_name' => 'Superdry', 'seo_url' => 'superdry'],
            ['store_name' => 'Smyths Toys', 'seo_url' => 'smyths-toys'],
            
            // T
            ['store_name' => 'Topshop', 'seo_url' => 'topshop'],
            ['store_name' => 'Tesco', 'seo_url' => 'tesco'],
            ['store_name' => 'The Body Shop', 'seo_url' => 'the-body-shop'],
            ['store_name' => 'TK Maxx', 'seo_url' => 'tk-maxx'],
            ['store_name' => 'TUI', 'seo_url' => 'tui'],
            
            // U
            ['store_name' => 'Uber Eats', 'seo_url' => 'uber-eats'],
            ['store_name' => 'Uniqlo', 'seo_url' => 'uniqlo'],
            ['store_name' => 'Under Armour', 'seo_url' => 'under-armour'],
            ['store_name' => 'Urban Outfitters', 'seo_url' => 'urban-outfitters'],
            ['store_name' => 'Ugg', 'seo_url' => 'ugg'],
            
            // V
            ['store_name' => 'Very', 'seo_url' => 'very'],
            ['store_name' => 'Vans', 'seo_url' => 'vans'],
            ['store_name' => 'Virgin Media', 'seo_url' => 'virgin-media'],
            ['store_name' => 'Vodafone', 'seo_url' => 'vodafone'],
            ['store_name' => 'Vue Cinemas', 'seo_url' => 'vue-cinemas'],
            
            // W
            ['store_name' => 'Walmart', 'seo_url' => 'walmart'],
            ['store_name' => 'WHSmith', 'seo_url' => 'whsmith'],
            ['store_name' => 'Waitrose', 'seo_url' => 'waitrose'],
            ['store_name' => 'Wickes', 'seo_url' => 'wickes'],
            ['store_name' => 'Wilko', 'seo_url' => 'wilko'],
            
            // X
            ['store_name' => 'Xbox', 'seo_url' => 'xbox'],
            ['store_name' => 'Xerox', 'seo_url' => 'xerox'],
            ['store_name' => 'Xero', 'seo_url' => 'xero'],
            ['store_name' => 'Xiaomi', 'seo_url' => 'xiaomi'],
            ['store_name' => 'Xfinity', 'seo_url' => 'xfinity'],
            
            // Y
            ['store_name' => 'YouTube', 'seo_url' => 'youtube'],
            ['store_name' => 'Yahoo', 'seo_url' => 'yahoo'],
            ['store_name' => 'YSL', 'seo_url' => 'ysl'],
            ['store_name' => 'Yves Rocher', 'seo_url' => 'yves-rocher'],
            ['store_name' => 'YOOX', 'seo_url' => 'yoox'],
            
            // Z
            ['store_name' => 'Zara', 'seo_url' => 'zara'],
            ['store_name' => 'Zalando', 'seo_url' => 'zalando'],
            ['store_name' => 'Zappos', 'seo_url' => 'zappos'],
            ['store_name' => 'ZooPlus', 'seo_url' => 'zooplus'],
            ['store_name' => 'Zizzi', 'seo_url' => 'zizzi'],
            
            // Numbers
            ['store_name' => '3 Mobile', 'seo_url' => '3-mobile'],
            ['store_name' => '7-Eleven', 'seo_url' => '7-eleven'],
            ['store_name' => '99p Stores', 'seo_url' => '99p-stores'],
        ];

        foreach ($stores as $storeData) {
            Store::firstOrCreate(
                ['seo_url' => $storeData['seo_url']],
                [
                    'store_name' => $storeData['store_name'],
                    'status' => true,
                    'sort_order' => rand(1, 100),
                    'show_trending' => rand(0, 1),
                    'featured' => rand(0, 1),
                    'recommended' => rand(0, 1),
                    'store_logo' => 'default-store.png',
                    'affiliate_url' => 'https://example.com/' . $storeData['seo_url'],
                    'content' => 'Sample content for ' . $storeData['store_name'],
                    'detail_description' => 'Detailed description for ' . $storeData['store_name'],
                    'meta_title' => $storeData['store_name'] . ' - Discount Codes & Vouchers',
                    'meta_description' => 'Get the best discount codes and vouchers for ' . $storeData['store_name'] . '. Save money on your purchases.',
                ]
            );
        }
    }
}