<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignProductsToChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all parent products (not variants)
        $products = DB::table('products')
            ->whereNull('parent_id')
            ->get(['id']);

        echo "Found {$products->count()} products to assign to channel\n";

        // Assign each product to the default channel (id=1)
        foreach ($products as $product) {
            DB::table('product_channels')->insertOrIgnore([
                'product_id' => $product->id,
                'channel_id' => 1,
            ]);
        }

        echo "✓ All products assigned to default channel!\n";
        
        // Verify
        $channelProducts = DB::table('product_channels')->count();
        echo "✓ Total product-channel assignments: {$channelProducts}\n";
    }
}
