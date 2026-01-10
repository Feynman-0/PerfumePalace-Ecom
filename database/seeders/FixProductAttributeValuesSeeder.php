<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixProductAttributeValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get attribute IDs
        $statusAttr = DB::table('attributes')->where('code', 'status')->first();
        $visibleAttr = DB::table('attributes')->where('code', 'visible_individually')->first();
        $urlKeyAttr = DB::table('attributes')->where('code', 'url_key')->first();
        $nameAttr = DB::table('attributes')->where('code', 'name')->first();

        echo "Status Attribute ID: {$statusAttr->id}\n";
        echo "Visible Individually Attribute ID: {$visibleAttr->id}\n";
        echo "URL Key Attribute ID: {$urlKeyAttr->id}\n";
        echo "Name Attribute ID: {$nameAttr->id}\n\n";

        // Get all parent products
        $products = DB::table('products')
            ->whereNull('parent_id')
            ->get(['id']);

        echo "Processing {$products->count()} products...\n\n";

        foreach ($products as $product) {
            // Get product details from product_flat
            $productFlat = DB::table('product_flat')
                ->where('product_id', $product->id)
                ->where('locale', 'en')
                ->where('channel', 'default')
                ->first();

            if (!$productFlat) {
                echo "⚠ Product {$product->id} not found in product_flat\n";
                continue;
            }

            // Insert status attribute value (boolean=1 for enabled)
            DB::table('product_attribute_values')->insertOrIgnore([
                'product_id' => $product->id,
                'attribute_id' => $statusAttr->id,
                'channel' => 'default',
                'locale' => 'en',
                'boolean_value' => 1,
            ]);

            // Insert visible_individually attribute value (boolean=1 for visible)
            DB::table('product_attribute_values')->insertOrIgnore([
                'product_id' => $product->id,
                'attribute_id' => $visibleAttr->id,
                'channel' => 'default',
                'locale' => 'en',
                'boolean_value' => 1,
            ]);

            // Insert url_key attribute value
            DB::table('product_attribute_values')->insertOrIgnore([
                'product_id' => $product->id,
                'attribute_id' => $urlKeyAttr->id,
                'channel' => 'default',
                'locale' => 'en',
                'text_value' => $productFlat->url_key,
            ]);

            // Insert name attribute value
            DB::table('product_attribute_values')->insertOrIgnore([
                'product_id' => $product->id,
                'attribute_id' => $nameAttr->id,
                'channel' => 'default',
                'locale' => 'en',
                'text_value' => $productFlat->name,
            ]);

            echo "✓ Product {$product->id}: {$productFlat->name}\n";
        }

        echo "\n✓ All product attribute values fixed!\n";
        
        // Verify
        $statusValues = DB::table('product_attribute_values')
            ->where('attribute_id', $statusAttr->id)
            ->count();
        $visibleValues = DB::table('product_attribute_values')
            ->where('attribute_id', $visibleAttr->id)
            ->count();
            
        echo "✓ Status attribute values: {$statusValues}\n";
        echo "✓ Visible attribute values: {$visibleValues}\n";
    }
}
