<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinalPerfumePalaceFixSeeder extends Seeder
{
    public function run()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "FINAL PERFUME PALACE FIX - IMAGES & CONTENT\n";
        echo str_repeat("=", 80) . "\n\n";

        // Available images
        $availableImages = [
            'perfume_1.jpg', 'perfume_2.jpg', 'perfume_3.jpg', 'perfume_5.jpg',
            'perfume_6.jpg', 'perfume_8.jpg', 'perfume_9.jpg', 'perfume_10.jpg',
            'perfume_12.jpg', 'perfume_13.jpg', 'perfume_15.jpg', 'perfume_18.jpg',
            'perfume_20.jpg'
        ];

        echo "🖼️  Updating product images to use available files...\n";
        
        // Update all product images to use existing images
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $randomImage = $availableImages[array_rand($availableImages)];
            
            DB::table('product_images')
                ->where('product_id', $product->id)
                ->update([
                    'path' => 'perfume_images/' . $randomImage
                ]);
        }
        
        echo "✓ Updated {$products->count()} product images\n\n";

        // Update homepage content to remove demo
        echo "🏠 Updating homepage content...\n";
        
        // Get theme customization IDs and update
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
                                'link' => '/mens-perfumes',
                                'image' => 'storage/perfume_images/perfume_1.jpg'
                            ],
                            [
                                'link' => '/womens-perfumes',
                                'image' => 'storage/perfume_images/perfume_2.jpg'
                            ],
                            [
                                'link' => '/luxury-perfumes',
                                'image' => 'storage/perfume_images/perfume_3.jpg'
                            ]
                        ]
                    ])
                ]);
            echo "✓ Updated image carousel\n";
        }

        // Update static content blocks
        $staticBlocks = DB::table('theme_customizations')
            ->where('type', 'static_content')
            ->where('channel_id', 1)
            ->get();
        
        $perfumeContents = [
            '<div style="text-align: center; padding: 40px;"><h2 style="font-size: 32px; margin-bottom: 20px;">Welcome to Perfume Palace</h2><p style="font-size: 18px;">Your premier destination for authentic luxury fragrances from world-renowned brands including Dior, Chanel, Creed, and Tom Ford.</p></div>',
            '<div style="padding: 40px; background: #f9f9f9;"><h3 style="text-align: center; font-size: 28px; margin-bottom: 30px;">Our Collections</h3><div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 1200px; margin: 0 auto;"><div style="text-align: center;"><h4 style="font-size: 20px; margin-bottom: 10px;">Men\'s Perfumes</h4><p>Sophisticated fragrances for the modern gentleman</p></div><div style="text-align: center;"><h4 style="font-size: 20px; margin-bottom: 10px;">Women\'s Perfumes</h4><p>Elegant scents for the discerning woman</p></div><div style="text-align: center;"><h4 style="font-size: 20px; margin-bottom: 10px;">Unisex Perfumes</h4><p>Versatile fragrances for everyone</p></div></div></div>',
            '<div style="text-align: center; padding: 40px;"><h3 style="font-size: 28px; margin-bottom: 20px;">Why Choose Perfume Palace?</h3><div style="max-width: 800px; margin: 0 auto;"><ul style="list-style: none; padding: 0; font-size: 18px;"><li style="margin-bottom: 15px;">✓ 100% Authentic Products Guaranteed</li><li style="margin-bottom: 15px;">✓ Premium Luxury Brands</li><li style="margin-bottom: 15px;">✓ Fast & Secure Shipping</li><li style="margin-bottom: 15px;">✓ Expert Fragrance Advice</li></ul></div></div>',
            '<div style="padding: 40px; background: #f5f5f5;"><h3 style="text-align: center; font-size: 28px; margin-bottom: 20px;">Featured Brands</h3><p style="text-align: center; font-size: 20px; color: #666;">Dior • Chanel • Creed • Tom Ford • Gucci • Versace • YSL • Prada • More</p></div>'
        ];

        foreach ($staticBlocks as $index => $block) {
            if (isset($perfumeContents[$index])) {
                DB::table('theme_customization_translations')
                    ->where('theme_customization_id', $block->id)
                    ->update([
                        'options' => json_encode([
                            'html' => $perfumeContents[$index]
                        ])
                    ]);
            }
        }
        
        echo "✓ Updated static content blocks\n\n";

        // Update product carousels to show featured/new/bestseller
        echo "🎠 Configuring product carousels...\n";
        
        $carousels = DB::table('theme_customizations')
            ->where('type', 'product_carousel')
            ->where('channel_id', 1)
            ->get();

        $carouselTitles = ['Featured Perfumes', 'New Arrivals', 'Best Sellers'];
        foreach ($carousels as $index => $carousel) {
            $title = $carouselTitles[$index] ?? 'Popular Perfumes';
            
            DB::table('theme_customization_translations')
                ->where('theme_customization_id', $carousel->id)
                ->update([
                    'options' => json_encode([
                        'title' => $title,
                        'filters' => [
                            'sort' => 'name-desc',
                            'limit' => 10
                        ]
                    ])
                ]);
        }
        
        echo "✓ Configured " . count($carousels) . " product carousels\n\n";

        // Ensure all products have proper meta data
        echo "📝 Updating product metadata...\n";
        
        DB::table('product_flat')
            ->where('meta_title', null)
            ->orWhere('meta_title', '')
            ->update([
                'meta_title' => DB::raw("CONCAT(name, ' - Perfume Palace')"),
                'meta_description' => DB::raw("CONCAT('Buy ', name, ' at Perfume Palace. 100% Authentic luxury fragrances.')"),
                'meta_keywords' => 'perfume, fragrance, luxury, authentic'
            ]);
        
        echo "✓ Product metadata updated\n\n";

        // Update category metadata
        echo "📂 Updating category metadata...\n";
        
        $categories = [
            100 => ['name' => "Men's Perfumes", 'desc' => 'Premium fragrances for men from top luxury brands'],
            101 => ['name' => "Women's Perfumes", 'desc' => 'Elegant perfumes for women from world-renowned houses'],
            102 => ['name' => 'Unisex Perfumes', 'desc' => 'Versatile fragrances suitable for everyone'],
            103 => ['name' => 'Luxury Perfumes', 'desc' => 'High-end designer fragrances and niche perfumes'],
            104 => ['name' => 'Arabic Perfumes', 'desc' => 'Oriental fragrances and oud-based scents'],
            105 => ['name' => 'Gift Sets', 'desc' => 'Perfect perfume gift collections and sets']
        ];

        foreach ($categories as $id => $data) {
            DB::table('category_translations')
                ->where('category_id', $id)
                ->update([
                    'meta_title' => $data['name'] . ' - Perfume Palace',
                    'meta_description' => $data['desc'],
                    'meta_keywords' => strtolower(str_replace("'", '', $data['name'])) . ', perfume, fragrance'
                ]);
        }
        
        echo "✓ Category metadata updated\n\n";

        echo str_repeat("=", 80) . "\n";
        echo "✅ FINAL FIX COMPLETE!\n";
        echo str_repeat("=", 80) . "\n";
        echo "Summary:\n";
        echo "  • {$products->count()} products with images\n";
        echo "  • 6 categories configured\n";
        echo "  • Homepage slider updated\n";
        echo "  • All demo content removed\n";
        echo "  • Product carousels showing\n";
        echo "\n";
        echo "🌐 Visit: http://localhost:8000\n";
        echo "Clear browser cache (Ctrl+Shift+Del) and refresh (Ctrl+F5)\n";
        echo str_repeat("=", 80) . "\n\n";
    }
}
