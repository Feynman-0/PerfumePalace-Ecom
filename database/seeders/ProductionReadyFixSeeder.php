<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductionReadyFixSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 PRODUCTION-READY FIX - FULL RECOVERY MODE');
        
        // Phase 1: Fix Database Integrity
        $this->fixDatabaseIntegrity();
        
        // Phase 2: Fix Product Images
        $this->fixProductImages();
        
        // Phase 3: Fix Category URL Paths
        $this->fixCategoryPaths();
        
        // Phase 4: Verify Product Visibility
        $this->verifyProductVisibility();
        
        // Phase 5: Fix Theme Customizations
        $this->fixThemeCustomizations();
        
        $this->command->info('🎉 PRODUCTION-READY FIX COMPLETE!');
    }

    private function fixDatabaseIntegrity()
    {
        $this->command->info('📊 Phase 1: Fixing Database Integrity...');

        // Ensure all products have proper channel and locale
        DB::table('product_flat')
            ->where('channel', '!=', 'default')
            ->orWhereNull('channel')
            ->update(['channel' => 'default']);

        DB::table('product_flat')
            ->where('locale', '!=', 'en')
            ->orWhereNull('locale')
            ->update(['locale' => 'en']);

        // Ensure all products are visible
        DB::table('product_flat')
            ->where('status', 0)
            ->update(['status' => 1]);

        DB::table('product_flat')
            ->where('visible_individually', 0)
            ->update(['visible_individually' => 1]);

        // Fix NULL url_keys
        $productsWithoutUrlKey = DB::table('product_flat')
            ->whereNull('url_key')
            ->orWhere('url_key', '')
            ->get();

        foreach ($productsWithoutUrlKey as $product) {
            $urlKey = strtolower(str_replace([' ', "'", '"', '&'], ['-', '', '', 'and'], $product->name));
            $urlKey = preg_replace('/[^a-z0-9\-]/', '', $urlKey);
            $urlKey = preg_replace('/-+/', '-', $urlKey);
            $urlKey = trim($urlKey, '-');

            DB::table('product_flat')
                ->where('id', $product->id)
                ->update(['url_key' => $urlKey . '-' . $product->id]);
        }

        $this->command->info('✅ Database integrity fixed');
    }

    private function fixProductImages()
    {
        $this->command->info('🖼️ Phase 2: Fixing Product Images...');

        // Get available images
        $imagePath = storage_path('app/public/perfume_images');
        $publicImagePath = public_path('storage/perfume_images');
        
        $availableImages = [];
        if (File::exists($imagePath)) {
            $availableImages = File::files($imagePath);
        } elseif (File::exists($publicImagePath)) {
            $availableImages = File::files($publicImagePath);
        }

        if (empty($availableImages)) {
            $this->command->warn('⚠️ No images found in storage');
            return;
        }

        $imageNames = array_map(function($file) {
            return basename($file);
        }, $availableImages);

        $this->command->info('Found ' . count($imageNames) . ' images');

        // Update all product images to use available images
        $products = DB::table('products')->where('parent_id', null)->get();
        
        foreach ($products as $index => $product) {
            $imageName = $imageNames[array_rand($imageNames)];
            
            // Update or create product image
            $existingImage = DB::table('product_images')
                ->where('product_id', $product->id)
                ->first();

            if ($existingImage) {
                DB::table('product_images')
                    ->where('product_id', $product->id)
                    ->update([
                        'path' => 'perfume_images/' . $imageName,
                        'type' => 'image'
                    ]);
            } else {
                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'path' => 'perfume_images/' . $imageName,
                    'type' => 'image'
                ]);
            }

            // Product flat doesn't need image updates - images are linked via product_images table
        }

        $this->command->info('✅ Product images fixed');
    }

    private function fixCategoryPaths()
    {
        $this->command->info('📁 Phase 3: Fixing Category Paths...');

        $categories = [
            ['id' => 100, 'slug' => 'mens-perfumes', 'name' => "Men's Perfumes"],
            ['id' => 101, 'slug' => 'womens-perfumes', 'name' => "Women's Perfumes"],
            ['id' => 102, 'slug' => 'unisex-perfumes', 'name' => 'Unisex Perfumes'],
            ['id' => 103, 'slug' => 'luxury-perfumes', 'name' => 'Luxury Perfumes'],
            ['id' => 104, 'slug' => 'arabic-perfumes', 'name' => 'Arabic Perfumes'],
            ['id' => 105, 'slug' => 'gift-sets', 'name' => 'Gift Sets'],
        ];

        foreach ($categories as $cat) {
            // Update category_translations
            DB::table('category_translations')
                ->where('category_id', $cat['id'])
                ->update([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'url_path' => $cat['slug']
                ]);

            // Update category itself
            DB::table('categories')
                ->where('id', $cat['id'])
                ->update(['status' => 1]);

            $this->command->info("✅ Fixed: {$cat['name']} ({$cat['slug']})");
        }

        $this->command->info('✅ Category paths fixed');
    }

    private function verifyProductVisibility()
    {
        $this->command->info('👁️ Phase 4: Verifying Product Visibility...');

        $stats = [
            'total' => DB::table('products')->where('parent_id', null)->count(),
            'flat' => DB::table('product_flat')->count(),
            'visible' => DB::table('product_flat')->where('status', 1)->where('visible_individually', 1)->count(),
            'with_images' => DB::table('product_images')->distinct('product_id')->count(),
            'in_categories' => DB::table('product_categories')->distinct('product_id')->count(),
        ];

        $this->command->table(
            ['Metric', 'Count'],
            [
                ['Total Products', $stats['total']],
                ['Product Flat Entries', $stats['flat']],
                ['Visible Products', $stats['visible']],
                ['Products with Images', $stats['with_images']],
                ['Products in Categories', $stats['in_categories']],
            ]
        );

        if ($stats['visible'] < $stats['total']) {
            $this->command->warn('⚠️ Some products are not visible!');
        }

        $this->command->info('✅ Visibility verification complete');
    }

    private function fixThemeCustomizations()
    {
        $this->command->info('🎨 Phase 5: Fixing Theme Customizations...');

        // Fix image carousel
        $imageCarousel = DB::table('theme_customizations')
            ->where('type', 'image_carousel')
            ->where('channel_id', 1)
            ->first();

        if ($imageCarousel) {
            DB::table('theme_customization_translations')
                ->where('theme_customization_id', $imageCarousel->id)
                ->update([
                    'options' => json_encode([
                        'images' => [
                            [
                                'link' => 'mens-perfumes',
                                'image' => 'storage/perfume_images/perfume_1.jpg',
                                'title' => 'Men\'s Luxury Collection',
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
                                'title' => 'Exclusive Luxury',
                                'sort_order' => 3
                            ]
                        ]
                    ])
                ]);
        }

        // Fix product carousels to actually show products
        $productCarousels = DB::table('theme_customizations')
            ->where('type', 'product_carousel')
            ->where('channel_id', 1)
            ->get();

        $carouselConfigs = [
            ['title' => 'Featured Perfumes', 'filters' => ['new' => '1', 'limit' => 12]],
            ['title' => 'New Arrivals', 'filters' => ['new' => '1', 'limit' => 12]],
            ['title' => 'Best Sellers', 'filters' => ['limit' => 12]],
        ];

        foreach ($productCarousels as $index => $carousel) {
            if (isset($carouselConfigs[$index])) {
                DB::table('theme_customization_translations')
                    ->where('theme_customization_id', $carousel->id)
                    ->update([
                        'options' => json_encode($carouselConfigs[$index])
                    ]);
            }
        }

        $this->command->info('✅ Theme customizations fixed');
    }
}
