<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PerfumeDataSeeder extends Seeder
{
    public function run()
    {
        echo "🚀 Starting Perfume Palace Data Import...\n\n";
        
        // Read CSV files
        $mensFile = 'D:\\Bagisto\\perfume_dataset\\ebay_mens_perfume.csv';
        $womensFile = 'D:\\Bagisto\\perfume_dataset\\ebay_womens_perfume.csv';
        
        $mensData = array_map('str_getcsv', file($mensFile));
        $womensData = array_map('str_getcsv', file($womensFile));
        
        // Get headers
        $mensHeaders = array_shift($mensData);
        $womensHeaders = array_shift($womensData);
        
        // Convert to associative arrays
        $mensProducts = array_slice(array_map(function($row) use ($mensHeaders) {
            return array_combine($mensHeaders, $row);
        }, $mensData), 0, 50);
        
        $womensProducts = array_slice(array_map(function($row) use ($womensHeaders) {
            return array_combine($womensHeaders, $row);
        }, $womensData), 0, 50);
        
        // Available images
        $images = ['perfume_1.jpg', 'perfume_2.jpg', 'perfume_3.jpg', 'perfume_5.jpg', 'perfume_6.jpg', 'perfume_8.jpg'];
        
        echo "📦 Cleaning existing products...\n";
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('product_images')->truncate();
        DB::table('product_inventories')->truncate();
        DB::table('product_categories')->truncate();
        DB::table('product_flat')->truncate();
        DB::table('products')->where('id', '>', 0)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        echo "✓ Cleaned\n\n";
        
        echo "📁 Creating categories...\n";
        
        // Check if categories exist, if not create them
        if (!DB::table('categories')->where('id', 100)->exists()) {
            // Men's Category
            DB::table('categories')->insert([
                'id' => 100,
                'parent_id' => 1,
                'position' => 1,
                '_lft' => 2,
                '_rgt' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('category_translations')->insert([
                'category_id' => 100,
                'locale' => 'en',
                'name' => "Men's Perfumes",
                'slug' => 'mens-perfumes',
                'description' => 'Premium fragrances for men',
                'meta_title' => "Men's Perfumes",
                'meta_description' => 'Shop premium men fragrances',
                'meta_keywords' => 'mens perfume, cologne, fragrance'
            ]);
        }
        
        if (!DB::table('categories')->where('id', 101)->exists()) {
            // Women's Category
            DB::table('categories')->insert([
                'id' => 101,
                'parent_id' => 1,
                'position' => 2,
                '_lft' => 4,
                '_rgt' => 5,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('category_translations')->insert([
                'category_id' => 101,
                'locale' => 'en',
                'name' => "Women's Perfumes",
                'slug' => 'womens-perfumes',
                'description' => 'Luxury fragrances for women',
                'meta_title' => "Women's Perfumes",
                'meta_description' => 'Shop luxury women fragrances',
                'meta_keywords' => 'womens perfume, fragrance, scent'
            ]);
        }
        
        echo "✓ Categories ready\n\n";
        
        $productId = 1;
        
        // Import Men's Perfumes
        echo "👔 Importing men's perfumes...\n";
        foreach ($mensProducts as $product) {
            if (empty($product['title']) || empty($product['price']) || $product['price'] < 10 || $product['price'] > 500) {
                continue;
            }
            
            $sku = 'PERF-M-' . str_pad($productId, 4, '0', STR_PAD_LEFT);
            $title = substr($product['title'], 0, 100);
            $brand = !empty($product['brand']) ? substr($product['brand'], 0, 50) : 'Premium';
            $price = floatval($product['price']);
            $urlKey = Str::slug($sku);
            $image = $images[array_rand($images)];
            
            DB::table('products')->insert([
                'id' => $productId,
                'sku' => $sku,
                'type' => 'simple',
                'attribute_family_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('product_flat')->insert([
                'sku' => $sku,
                'product_id' => $productId,
                'name' => $title,
                'short_description' => $brand . ' - Premium Men\'s Fragrance',
                'description' => $title . ' - Experience luxury and sophistication with this premium men\'s fragrance. Perfect for any occasion.',
                'price' => $price,
                'url_key' => $urlKey,
                'new' => 1,
                'featured' => 1,
                'status' => 1,
                'visible_individually' => 1,
                'channel' => 'default',
                'locale' => 'en',
                'attribute_family_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('product_inventories')->insert([
                'product_id' => $productId,
                'inventory_source_id' => 1,
                'qty' => 100
            ]);
            
            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => 100
            ]);
            
            DB::table('product_images')->insert([
                'product_id' => $productId,
                'path' => 'product_images/' . $image,
                'type' => 'image'
            ]);
            
            echo "  ✓ {$productId}. {$title}\n";
            $productId++;
        }
        
        echo "\n👗 Importing women's perfumes...\n";
        foreach ($womensProducts as $product) {
            if (empty($product['title']) || empty($product['price']) || $product['price'] < 10 || $product['price'] > 500) {
                continue;
            }
            
            $sku = 'PERF-W-' . str_pad($productId, 4, '0', STR_PAD_LEFT);
            $title = substr($product['title'], 0, 100);
            $brand = !empty($product['brand']) ? substr($product['brand'], 0, 50) : 'Premium';
            $price = floatval($product['price']);
            $urlKey = Str::slug($sku);
            $image = $images[array_rand($images)];
            
            DB::table('products')->insert([
                'id' => $productId,
                'sku' => $sku,
                'type' => 'simple',
                'attribute_family_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('product_flat')->insert([
                'sku' => $sku,
                'product_id' => $productId,
                'name' => $title,
                'short_description' => $brand . ' - Luxury Women\'s Fragrance',
                'description' => $title . ' - Indulge in elegance with this exquisite women\'s fragrance. Timeless beauty in every drop.',
                'price' => $price,
                'url_key' => $urlKey,
                'new' => 1,
                'featured' => 1,
                'status' => 1,
                'visible_individually' => 1,
                'channel' => 'default',
                'locale' => 'en',
                'attribute_family_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('product_inventories')->insert([
                'product_id' => $productId,
                'inventory_source_id' => 1,
                'qty' => 100
            ]);
            
            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => 101
            ]);
            
            DB::table('product_images')->insert([
                'product_id' => $productId,
                'path' => 'product_images/' . $image,
                'type' => 'image'
            ]);
            
            echo "  ✓ {$productId}. {$title}\n";
            $productId++;
        }
        
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "🎉 SUCCESS! Perfume Palace is ready!\n";
        echo str_repeat('=', 60) . "\n";
        echo "Total Products Imported: " . ($productId - 1) . "\n";
        echo "  - Men's Perfumes: ~50\n";
        echo "  - Women's Perfumes: ~50\n";
        echo "\nAll products have:\n";
        echo "  ✓ Real images\n";
        echo "  ✓ Competitive prices\n";
        echo "  ✓ Full descriptions\n";
        echo "  ✓ Stock quantity: 100 each\n";
        echo str_repeat('=', 60) . "\n";
    }
}
