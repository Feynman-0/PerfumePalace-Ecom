<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixAllDisplayIssuesSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔧 Fixing ALL display issues...');

        // Fix 1: Update category URL paths
        $this->fixCategoryUrlPaths();

        // Fix 2: Fix product flat URL keys
        $this->fixProductUrlKeys();

        // Fix 3: Update slider for better image display
        $this->fixSliderDisplay();

        // Fix 4: Fix category images
        $this->addCategoryImages();

        $this->command->info('🎉 All display issues fixed!');
    }

    private function fixCategoryUrlPaths()
    {
        $this->command->info('📂 Fixing category URL paths...');

        $categories = [
            ['id' => 100, 'slug' => 'mens-perfumes'],
            ['id' => 101, 'slug' => 'womens-perfumes'],
            ['id' => 102, 'slug' => 'unisex-perfumes'],
            ['id' => 103, 'slug' => 'luxury-perfumes'],
            ['id' => 104, 'slug' => 'arabic-perfumes'],
            ['id' => 105, 'slug' => 'gift-sets'],
        ];

        foreach ($categories as $category) {
            // Update category_translations with proper url_path
            DB::table('category_translations')
                ->where('category_id', $category['id'])
                ->update([
                    'url_path' => $category['slug'],
                    'slug' => $category['slug']
                ]);

            $this->command->info("✅ Fixed category {$category['id']}: {$category['slug']}");
        }

        $this->command->info('✅ Category URL paths fixed!');
    }

    private function fixProductUrlKeys()
    {
        $this->command->info('🔧 Fixing product URL keys...');

        // Get all products without proper url_key
        $products = DB::table('product_flat')
            ->whereNull('url_key')
            ->orWhere('url_key', '')
            ->get();

        foreach ($products as $product) {
            $urlKey = strtolower(str_replace([' ', "'", '"'], ['-', '', ''], $product->name));
            $urlKey = preg_replace('/[^a-z0-9\-]/', '', $urlKey);
            $urlKey = preg_replace('/-+/', '-', $urlKey);

            DB::table('product_flat')
                ->where('id', $product->id)
                ->update(['url_key' => $urlKey]);
        }

        // Also update any product with duplicate url_keys
        $allProducts = DB::table('product_flat')->get();
        foreach ($allProducts as $product) {
            if (empty($product->url_key)) {
                $urlKey = strtolower(str_replace([' ', "'", '"'], ['-', '', ''], $product->name));
                $urlKey = preg_replace('/[^a-z0-9\-]/', '', $urlKey);
                $urlKey = preg_replace('/-+/', '-', $urlKey);

                DB::table('product_flat')
                    ->where('id', $product->id)
                    ->update(['url_key' => $urlKey . '-' . $product->id]);
            }
        }

        $this->command->info('✅ Product URL keys fixed!');
    }

    private function fixSliderDisplay()
    {
        $this->command->info('🖼️ Optimizing slider display...');

        // Update image carousel for better display
        $sliderContent = [
            'images' => [
                [
                    'link' => 'mens-perfumes',
                    'image' => 'storage/perfume_images/perfume_1.jpg',
                    'title' => 'Discover Men\'s Luxury Fragrances',
                    'content' => 'Premium collection from top designers',
                    'sort_order' => 1
                ],
                [
                    'link' => 'womens-perfumes',
                    'image' => 'storage/perfume_images/perfume_2.jpg',
                    'title' => 'Women\'s Signature Scents',
                    'content' => 'Elegance in every bottle',
                    'sort_order' => 2
                ],
                [
                    'link' => 'luxury-perfumes',
                    'image' => 'storage/perfume_images/perfume_3.jpg',
                    'title' => 'Luxury Collection',
                    'content' => 'Exclusive designer fragrances',
                    'sort_order' => 3
                ]
            ]
        ];

        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 1)
            ->update(['options' => json_encode($sliderContent)]);

        $this->command->info('✅ Slider optimized!');
    }

    private function addCategoryImages()
    {
        $this->command->info('📸 Adding category images...');

        $categoryImages = [
            100 => 'perfume_images/perfume_5.jpg',  // Men's
            101 => 'perfume_images/perfume_6.jpg',  // Women's
            102 => 'perfume_images/perfume_8.jpg',  // Unisex
            103 => 'perfume_images/perfume_9.jpg',  // Luxury
            104 => 'perfume_images/perfume_10.jpg', // Arabic
            105 => 'perfume_images/perfume_12.jpg', // Gift Sets
        ];

        foreach ($categoryImages as $categoryId => $imagePath) {
            // Check if category exists
            $category = DB::table('categories')->where('id', $categoryId)->first();
            if ($category) {
                // Update category with logo_path (Bagisto uses this field)
                DB::table('categories')
                    ->where('id', $categoryId)
                    ->update([
                        'logo_path' => $imagePath,
                        'banner_path' => $imagePath
                    ]);

                $this->command->info("✅ Added image for category {$categoryId}");
            }
        }

        $this->command->info('✅ Category images added!');
    }
}
