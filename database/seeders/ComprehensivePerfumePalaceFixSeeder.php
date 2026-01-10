<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComprehensivePerfumePalaceFixSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting Comprehensive Perfume Palace Fix...');

        // Step 1: Fix Product Carousels
        $this->fixProductCarousels();

        // Step 2: Fix Slider Images
        $this->fixSliderImages();

        // Step 3: Add 20 More Products
        $this->addMoreProducts();

        // Step 4: Refresh product_flat
        $this->refreshProductFlat();

        $this->command->info('🎉 All fixes completed successfully!');
    }

    private function fixProductCarousels()
    {
        $this->command->info('📦 Fixing product carousels...');

        // Get some product IDs to feature
        $featuredProducts = DB::table('products')
            ->where('parent_id', null)
            ->limit(10)
            ->pluck('id')
            ->toArray();

        // Update Featured Perfumes carousel (ID 4)
        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 4)
            ->update([
                'options' => json_encode([
                    'title' => 'Featured Perfumes',
                    'filters' => [
                        'limit' => 12
                    ]
                ])
            ]);

        // Update New Arrivals carousel (ID 7)
        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 7)
            ->update([
                'options' => json_encode([
                    'title' => 'New Arrivals',
                    'filters' => [
                        'sort' => 'created_at-desc',
                        'limit' => 12
                    ]
                ])
            ]);

        // Update Best Sellers carousel (ID 9)
        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 9)
            ->update([
                'options' => json_encode([
                    'title' => 'Best Sellers',
                    'filters' => [
                        'sort' => 'price-desc',
                        'limit' => 12
                    ]
                ])
            ]);

        $this->command->info('✅ Product carousels fixed!');
    }

    private function fixSliderImages()
    {
        $this->command->info('🖼️ Fixing slider images...');

        // Update image carousel with properly sized images
        $sliderContent = [
            'images' => [
                [
                    'link' => 'mens-perfumes',
                    'image' => 'storage/perfume_images/perfume_1.jpg',
                    'title' => 'Men\'s Luxury Fragrances',
                    'sort_order' => 1
                ],
                [
                    'link' => 'womens-perfumes',
                    'image' => 'storage/perfume_images/perfume_2.jpg',
                    'title' => 'Women\'s Signature Scents',
                    'sort_order' => 2
                ],
                [
                    'link' => 'luxury-perfumes',
                    'image' => 'storage/perfume_images/perfume_3.jpg',
                    'title' => 'Luxury Collection',
                    'sort_order' => 3
                ]
            ]
        ];

        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 1)
            ->update([
                'options' => json_encode($sliderContent)
            ]);

        $this->command->info('✅ Slider images fixed with proper structure!');
    }

    private function addMoreProducts()
    {
        $this->command->info('➕ Adding 20 more premium perfumes...');

        $newPerfumes = [
            // Men's Perfumes (10)
            ['Dior Homme Intense', 'Dior', 'men', 92.00, 'woody oriental', 'perfume_5.jpg'],
            ['Paco Rabanne Invictus', 'Paco Rabanne', 'men', 68.00, 'woody aquatic', 'perfume_6.jpg'],
            ['Carolina Herrera Bad Boy', 'Carolina Herrera', 'men', 79.00, 'spicy oriental', 'perfume_8.jpg'],
            ['Versace Pour Homme', 'Versace', 'men', 58.00, 'aromatic fougere', 'perfume_9.jpg'],
            ['Giorgio Armani Stronger With You', 'Giorgio Armani', 'men', 76.00, 'sweet spicy', 'perfume_10.jpg'],
            ['Tom Ford Noir', 'Tom Ford', 'men', 135.00, 'oriental spicy', 'perfume_12.jpg'],
            ['Yves Saint Laurent Y', 'Yves Saint Laurent', 'men', 82.00, 'woody aromatic', 'perfume_13.jpg'],
            ['Gucci Guilty Black', 'Gucci', 'men', 74.00, 'aromatic green', 'perfume_15.jpg'],
            ['Burberry Touch', 'Burberry', 'men', 52.00, 'woody floral', 'perfume_18.jpg'],
            ['Ralph Lauren Polo Red', 'Ralph Lauren', 'men', 64.00, 'spicy woody', 'perfume_20.jpg'],
            
            // Women's Perfumes (10)
            ['Chanel Chance Eau Tendre', 'Chanel', 'women', 98.00, 'floral fruity', 'perfume_1.jpg'],
            ['Dior Miss Dior', 'Dior', 'women', 92.00, 'chypre floral', 'perfume_2.jpg'],
            ['Giorgio Armani My Way', 'Giorgio Armani', 'women', 86.00, 'floral white', 'perfume_3.jpg'],
            ['Lancome Idole', 'Lancome', 'women', 85.00, 'floral clean', 'perfume_5.jpg'],
            ['Yves Saint Laurent Libre', 'Yves Saint Laurent', 'women', 94.00, 'floral lavender', 'perfume_6.jpg'],
            ['Gucci Flora Gorgeous Gardenia', 'Gucci', 'women', 88.00, 'floral white', 'perfume_8.jpg'],
            ['Prada La Femme', 'Prada', 'women', 89.00, 'floral oriental', 'perfume_9.jpg'],
            ['Burberry Brit', 'Burberry', 'women', 62.00, 'floral fruity', 'perfume_10.jpg'],
            ['Marc Jacobs Perfect', 'Marc Jacobs', 'women', 76.00, 'floral fresh', 'perfume_12.jpg'],
            ['Versace Bright Crystal', 'Versace', 'women', 68.00, 'floral fruity', 'perfume_13.jpg'],
        ];

        $existingCount = DB::table('products')->where('parent_id', null)->count();
        $skuStart = $existingCount + 1;

        foreach ($newPerfumes as $index => $perfume) {
            $sku = 'PERF-' . str_pad($skuStart + $index, 4, '0', STR_PAD_LEFT);
            
            // Determine category based on gender
            $categoryId = $perfume[2] === 'men' ? 100 : 101; // 100=Men's, 101=Women's
            
            // Insert product
            $productId = DB::table('products')->insertGetId([
                'type' => 'simple',
                'attribute_family_id' => 1,
                'sku' => $sku,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert product flat data
            DB::table('product_flat')->insert([
                'product_id' => $productId,
                'sku' => $sku,
                'name' => $perfume[0],
                'short_description' => '<p>Premium ' . $perfume[4] . ' fragrance by ' . $perfume[1] . '</p>',
                'description' => '<p>Experience the luxurious scent of ' . $perfume[0] . ' by ' . $perfume[1] . '. This exquisite ' . $perfume[4] . ' fragrance combines sophistication with timeless elegance. Perfect for any occasion, it leaves a lasting impression with its unique blend of notes.</p>',
                'price' => $perfume[3],
                'special_price' => null,
                'status' => 1,
                'visible_individually' => 1,
                'url_key' => strtolower(str_replace([' ', "'"], ['-', ''], $perfume[0])),
                'new' => 1,
                'featured' => rand(0, 1),
                'channel' => 'default',
                'locale' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link to category
            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => $categoryId,
            ]);

            // Add inventory
            DB::table('product_inventories')->insert([
                'qty' => 50,
                'product_id' => $productId,
                'inventory_source_id' => 1,
                'vendor_id' => 0,
            ]);

            // Add product image
            DB::table('product_images')->insert([
                'type' => 'image',
                'path' => 'perfume_images/' . $perfume[5],
                'product_id' => $productId,
            ]);

            // Add attribute values (name)
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => 9, // name
                'locale' => 'en',
                'channel' => 'default',
                'text_value' => $perfume[0],
            ]);

            // Add attribute values (description)
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => 10, // description
                'locale' => 'en',
                'channel' => 'default',
                'text_value' => '<p>Experience the luxurious scent of ' . $perfume[0] . ' by ' . $perfume[1] . '.</p>',
            ]);

            // Add attribute values (price)
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => 11, // price
                'locale' => null,
                'channel' => null,
                'float_value' => $perfume[3],
            ]);
        }

        $this->command->info('✅ Added 20 new premium perfumes!');
    }

    private function refreshProductFlat()
    {
        $this->command->info('🔄 Refreshing product flat table...');
        
        // Update all product_flat entries to ensure they're visible
        DB::table('product_flat')
            ->where('status', 1)
            ->update([
                'visible_individually' => 1,
                'channel' => 'default',
                'locale' => 'en',
            ]);

        $totalProducts = DB::table('product_flat')
            ->where('status', 1)
            ->where('visible_individually', 1)
            ->count();

        $this->command->info("✅ Total visible products: {$totalProducts}");
    }
}
