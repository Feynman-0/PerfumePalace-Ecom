<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPricesAndDescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Perfume descriptions
        $descriptions = [
            'Experience the timeless elegance of this exquisite fragrance. A perfect blend of sophistication and luxury that captures attention and leaves a lasting impression.',
            'Indulge in the captivating aroma that embodies modern luxury. This fragrance is crafted for those who appreciate the finer things in life.',
            'A signature scent that speaks volumes about your refined taste. Fresh, vibrant, and unforgettable - perfect for any occasion.',
            'Discover the essence of luxury in every spray. This premium fragrance combines rare ingredients to create an intoxicating and memorable experience.',
            'Elevate your presence with this sophisticated blend. A harmonious composition that balances strength and subtlety perfectly.',
            'Immerse yourself in pure indulgence. This luxurious fragrance is designed for those who command attention wherever they go.',
            'A modern classic that transcends trends. Experience the perfect balance of tradition and contemporary elegance.',
            'Unleash your confidence with this powerful yet refined scent. Crafted for individuals who leave a lasting impression.',
            'Experience luxury redefined. This exquisite fragrance captures the essence of timeless sophistication and modern charm.',
            'A celebration of elegance and style. This captivating scent is perfect for those who appreciate quality and distinction.',
            'Transform every moment into something special. This enchanting fragrance is your perfect companion for life\'s memorable occasions.',
            'Discover the art of fine perfumery. A masterful blend that showcases exceptional craftsmanship and attention to detail.',
            'Experience the allure of this captivating fragrance. Designed for those who embrace luxury and elegance in every aspect of life.',
            'A symphony of exquisite notes that creates an unforgettable olfactory experience. Perfect for the discerning fragrance enthusiast.',
            'Indulge in the ultimate expression of luxury. This premium scent embodies sophistication, elegance, and timeless beauty.',
        ];

        // Get all products with $0.00 prices
        $products = DB::table('product_flat')
            ->where('price', 0)
            ->orWhereNull('price')
            ->get();

        echo "Found {$products->count()} products with zero/null prices\n";

        foreach ($products as $product) {
            // Generate random price based on product category
            $price = $this->generatePrice($product->name);
            
            // Get random description
            $description = $descriptions[array_rand($descriptions)];
            
            // Update product_flat table
            DB::table('product_flat')
                ->where('product_id', $product->product_id)
                ->update([
                    'price' => $price,
                    'short_description' => $description,
                    'description' => $description . ' ' . $this->generateLongDescription($product->name),
                ]);

            // Update products table
            DB::table('products')
                ->where('id', $product->product_id)
                ->update([
                    'price' => $price,
                ]);

            // Update product_attribute_values for price
            $priceAttr = DB::table('attributes')->where('code', 'price')->first();
            if ($priceAttr) {
                DB::table('product_attribute_values')->updateOrInsert(
                    [
                        'product_id' => $product->product_id,
                        'attribute_id' => $priceAttr->id,
                        'channel' => 'default',
                        'locale' => 'en',
                    ],
                    [
                        'float_value' => $price,
                    ]
                );
            }

            // Update product_attribute_values for description
            $descAttr = DB::table('attributes')->where('code', 'short_description')->first();
            if ($descAttr) {
                DB::table('product_attribute_values')->updateOrInsert(
                    [
                        'product_id' => $product->product_id,
                        'attribute_id' => $descAttr->id,
                        'channel' => 'default',
                        'locale' => 'en',
                    ],
                    [
                        'text_value' => $description,
                    ]
                );
            }

            echo "✓ {$product->name}: \${$price}\n";
        }

        // Also update products that might have prices but no descriptions
        $productsNoDesc = DB::table('product_flat')
            ->where(function($q) {
                $q->whereNull('short_description')
                  ->orWhere('short_description', '');
            })
            ->get();

        echo "\nAdding descriptions to {$productsNoDesc->count()} products without descriptions\n";

        foreach ($productsNoDesc as $product) {
            $description = $descriptions[array_rand($descriptions)];
            
            DB::table('product_flat')
                ->where('product_id', $product->product_id)
                ->update([
                    'short_description' => $description,
                    'description' => $description . ' ' . $this->generateLongDescription($product->name),
                ]);

            echo "✓ Added description to: {$product->name}\n";
        }

        echo "\n✓ All prices and descriptions updated!\n";
    }

    private function generatePrice($productName)
    {
        $name = strtolower($productName);
        
        // Luxury brands get higher prices
        if (strpos($name, 'creed') !== false || strpos($name, 'tom ford') !== false) {
            return round(mt_rand(25000, 45000) / 100, 2); // $250-$450
        }
        
        if (strpos($name, 'chanel') !== false || strpos($name, 'dior') !== false) {
            return round(mt_rand(12000, 25000) / 100, 2); // $120-$250
        }
        
        if (strpos($name, 'amouage') !== false || strpos($name, 'maison') !== false) {
            return round(mt_rand(18000, 35000) / 100, 2); // $180-$350
        }
        
        // Regular luxury perfumes
        return round(mt_rand(7500, 15000) / 100, 2); // $75-$150
    }

    private function generateLongDescription($productName)
    {
        $details = [
            'Meticulously crafted by master perfumers, this fragrance features premium ingredients sourced from around the world.',
            'The composition reveals itself in layers, offering a unique experience that evolves throughout the day.',
            'Housed in an elegant bottle that reflects the sophistication of the fragrance within.',
            'Long-lasting formula ensures you stay enveloped in luxury from morning to night.',
            'Perfect for both special occasions and everyday wear, adapting beautifully to any setting.',
            'An investment in quality that stands the test of time, becoming your signature scent.',
            'Expertly balanced notes create a harmonious blend that is both distinctive and universally appealing.',
            'The ultimate accessory for the modern individual who values quality and craftsmanship.',
        ];

        return $details[array_rand($details)];
    }
}
