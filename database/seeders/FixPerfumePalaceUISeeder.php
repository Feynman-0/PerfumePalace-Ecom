<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixPerfumePalaceUISeeder extends Seeder
{
    public function run()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "FIXING PERFUME PALACE UI - REMOVING ALL DEMO CONTENT\n";
        echo str_repeat("=", 80) . "\n\n";

        // 1. Update theme customizations
        echo "🎨 Updating theme customizations...\n";
        
        // Image carousel (slider)
        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 1)
            ->update([
                'options' => json_encode([
                    'images' => [
                        [
                            'image' => 'themes/shop/default/build/assets/perfume-palace-logo.svg',
                            'link' => 'mens-perfumes',
                            'title' => 'Premium Luxury Fragrances'
                        ],
                        [
                            'image' => 'themes/shop/default/build/assets/perfume-palace-logo.svg',
                            'link' => 'womens-perfumes',
                            'title' => 'Exquisite Perfumes for Women'
                        ],
                        [
                            'image' => 'themes/shop/default/build/assets/perfume-palace-logo.svg',
                            'link' => 'luxury-perfumes',
                            'title' => 'Discover Luxury Scents'
                        ]
                    ]
                ])
            ]);

        // Static content - Update all instances
        $staticContents = [
            2 => [
                'html' => '<div class="container mt-8"><h2 class="text-3xl font-bold text-center mb-4">Welcome to Perfume Palace</h2><p class="text-center text-lg">Your premier destination for authentic luxury fragrances from world-renowned brands.</p></div>'
            ],
            5 => [
                'html' => '<div class="container"><h3 class="text-2xl font-bold mb-4">Discover Our Collections</h3><div class="grid grid-cols-3 gap-4"><div class="text-center"><h4>Men\'s Perfumes</h4><p>Sophisticated fragrances</p></div><div class="text-center"><h4>Women\'s Perfumes</h4><p>Elegant scents</p></div><div class="text-center"><h4>Unisex Perfumes</h4><p>Versatile fragrances</p></div></div></div>'
            ],
            6 => [
                'html' => '<div class="container text-center"><h3 class="text-2xl font-bold mb-4">Why Choose Perfume Palace?</h3><ul class="list-none"><li>✓ 100% Authentic Products</li><li>✓ Premium Luxury Brands</li><li>✓ Fast Shipping</li><li>✓ Expert Advice</li></ul></div>'
            ],
            8 => [
                'html' => '<div class="container"><h3 class="text-2xl font-bold text-center mb-4">Featured Brands</h3><p class="text-center">Dior • Chanel • Creed • Tom Ford • Gucci • YSL • Versace • More</p></div>'
            ],
            10 => [
                'html' => '<div class="container text-center"><h3 class="text-2xl font-bold mb-4">About Perfume Palace</h3><p>We curate the finest fragrances from around the world. Every product is 100% authentic and sourced directly from authorized distributors.</p></div>'
            ]
        ];

        foreach ($staticContents as $id => $content) {
            DB::table('theme_customization_translations')
                ->where('theme_customization_id', $id)
                ->update([
                    'options' => json_encode([
                        'html' => $content['html'],
                        'css' => '.container { max-width: 1200px; margin: 0 auto; padding: 20px; }'
                    ])
                ]);
        }

        // Product carousels - link to our products
        $productCarousels = [4, 7, 9];
        foreach ($productCarousels as $carouselId) {
            $title = match($carouselId) {
                4 => 'Featured Perfumes',
                7 => 'New Arrivals',
                9 => 'Best Sellers',
                default => 'Popular Perfumes'
            };
            
            DB::table('theme_customization_translations')
                ->where('theme_customization_id', $carouselId)
                ->update([
                    'options' => json_encode([
                        'title' => $title,
                        'filters' => [
                            'limit' => 10,
                            'sort' => 'name-asc'
                        ]
                    ])
                ]);
        }

        // Category carousel
        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 3)
            ->update([
                'options' => json_encode([
                    'filters' => [
                        'parent_id' => 1,
                        'limit' => 6
                    ]
                ])
            ]);

        echo "✓ Theme customizations updated\n\n";

        // 2. Update site title and meta
        echo "📝 Updating site metadata...\n";
        
        DB::table('core_config')->updateOrInsert(
            ['code' => 'general.general.general.store_name', 'channel_code' => 'default', 'locale_code' => 'en'],
            ['value' => 'Perfume Palace', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('core_config')->updateOrInsert(
            ['code' => 'general.content.shop.name', 'channel_code' => 'default', 'locale_code' => 'en'],
            ['value' => 'Perfume Palace', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('core_config')->updateOrInsert(
            ['code' => 'general.content.shop.title', 'channel_code' => 'default', 'locale_code' => 'en'],
            ['value' => 'Perfume Palace - Luxury Fragrances', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('core_config')->updateOrInsert(
            ['code' => 'general.content.shop.description', 'channel_code' => 'default', 'locale_code' => 'en'],
            ['value' => 'Shop premium perfumes and luxury fragrances from top brands. 100% authentic products.', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('core_config')->updateOrInsert(
            ['code' => 'general.content.shop.keywords', 'channel_code' => 'default', 'locale_code' => 'en'],
            ['value' => 'perfume, fragrance, cologne, luxury scents, authentic perfumes', 'created_at' => now(), 'updated_at' => now()]
        );

        echo "✓ Site metadata updated\n\n";

        // 3. Ensure all products are linked to categories
        echo "🔗 Verifying product-category links...\n";
        
        $productsWithoutCategories = DB::table('products')
            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->whereNull('product_categories.product_id')
            ->count();

        if ($productsWithoutCategories > 0) {
            echo "  ⚠ Found {$productsWithoutCategories} products without categories\n";
            echo "  Assigning to Men's Perfumes category...\n";
            
            $unlinkedProducts = DB::table('products')
                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->whereNull('product_categories.product_id')
                ->select('products.id')
                ->get();
            
            foreach ($unlinkedProducts as $product) {
                DB::table('product_categories')->insert([
                    'product_id' => $product->id,
                    'category_id' => 100 // Men's Perfumes
                ]);
            }
        }
        
        echo "✓ All products linked to categories\n\n";

        // 4. Update footer
        echo "📄 Updating footer content...\n";
        
        DB::table('theme_customization_translations')
            ->where('theme_customization_id', 11)
            ->update([
                'options' => json_encode([
                    'column_1' => [
                        ['title' => 'About Us', 'url' => 'about-us'],
                        ['title' => 'Contact Us', 'url' => 'contact-us'],
                        ['title' => 'Customer Service', 'url' => 'customer-service']
                    ],
                    'column_2' => [
                        ['title' => "Men's Perfumes", 'url' => 'mens-perfumes'],
                        ['title' => "Women's Perfumes", 'url' => 'womens-perfumes'],
                        ['title' => 'Unisex Perfumes', 'url' => 'unisex-perfumes']
                    ],
                    'column_3' => [
                        ['title' => 'Luxury Perfumes', 'url' => 'luxury-perfumes'],
                        ['title' => 'Arabic Perfumes', 'url' => 'arabic-perfumes'],
                        ['title' => 'Gift Sets', 'url' => 'gift-sets']
                    ]
                ])
            ]);

        echo "✓ Footer updated\n\n";

        // 5. Verify images exist
        echo "🖼️  Checking product images...\n";
        
        $imagesCount = DB::table('product_images')->count();
        echo "  Found {$imagesCount} product images in database\n";
        
        $imagesDir = 'D:\\Bagisto\\public\\storage\\perfume_images';
        if (is_dir($imagesDir)) {
            $imageFiles = glob($imagesDir . '/*.jpg');
            echo "  Found " . count($imageFiles) . " image files on disk\n";
        }
        
        echo "\n";

        // 6. Create storage link if not exists
        echo "🔗 Creating storage link...\n";
        $publicStorage = 'D:\\Bagisto\\public\\storage';
        $storageApp = 'D:\\Bagisto\\storage\\app\\public';
        
        if (!file_exists($publicStorage)) {
            if (is_dir($storageApp)) {
                symlink($storageApp, $publicStorage);
                echo "✓ Storage link created\n";
            } else {
                mkdir($storageApp, 0755, true);
                symlink($storageApp, $publicStorage);
                echo "✓ Storage directories created and linked\n";
            }
        } else {
            echo "✓ Storage link already exists\n";
        }
        
        echo "\n";

        echo str_repeat("=", 80) . "\n";
        echo "✅ UI FIX COMPLETE!\n";
        echo str_repeat("=", 80) . "\n";
        echo "Next steps:\n";
        echo "1. Clear cache: php artisan cache:clear\n";
        echo "2. Clear config: php artisan config:clear\n";
        echo "3. Clear view: php artisan view:clear\n";
        echo "4. Refresh browser (Ctrl+F5)\n";
        echo str_repeat("=", 80) . "\n\n";
    }
}
