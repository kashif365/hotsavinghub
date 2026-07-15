<?php

namespace Database\Seeders;

use App\Models\Networks;
use Illuminate\Database\Seeder;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $networks = [
            [
                'name' => 'Commission Junction',
                'affiliate_id' => 'CJ123456',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'ShareASale',
                'affiliate_id' => 'SAS789012',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'ClickBank',
                'affiliate_id' => 'CB345678',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Amazon Associates',
                'affiliate_id' => 'AMZ901234',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'eBay Partner Network',
                'affiliate_id' => 'EBAY567890',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Rakuten Advertising',
                'affiliate_id' => 'RAK123456',
                'status' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Impact Radius',
                'affiliate_id' => 'IMP789012',
                'status' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Awin',
                'affiliate_id' => 'AWIN345678',
                'status' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'FlexOffers',
                'affiliate_id' => 'FLEX901234',
                'status' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Webgains',
                'affiliate_id' => 'WEB567890',
                'status' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'TradeDoubler',
                'affiliate_id' => 'TD123456',
                'status' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Skimlinks',
                'affiliate_id' => 'SKIM789012',
                'status' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'VigLink',
                'affiliate_id' => 'VIG345678',
                'status' => true,
                'sort_order' => 13,
            ],
            [
                'name' => 'LinkConnector',
                'affiliate_id' => 'LC901234',
                'status' => true,
                'sort_order' => 14,
            ],
            [
                'name' => 'Partnerize',
                'affiliate_id' => 'PZ567890',
                'status' => true,
                'sort_order' => 15,
            ],
        ];

        foreach ($networks as $networkData) {
            Networks::firstOrCreate(
                ['name' => $networkData['name']],
                $networkData
            );
        }
    }
}