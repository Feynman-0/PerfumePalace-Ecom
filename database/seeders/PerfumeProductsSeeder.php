<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\LocaleRepository;

class PerfumeProductsSeeder extends Seeder
{
    protected $productRepository;
    protected $attributeFamilyRepository;
    protected $categoryRepository;
    protected $channelRepository;
    protected $localeRepository;

    public function __construct(
        ProductRepository $productRepository,
        AttributeFamilyRepository $attributeFamilyRepository,
        CategoryRepository $categoryRepository,
        ChannelRepository $channelRepository,
        LocaleRepository $localeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->attributeFamilyRepository = $attributeFamilyRepository;
        $this->categoryRepository = $categoryRepository;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;
    }

    public function run()
    {
        $attributeFamily = $this->attributeFamilyRepository->findOneByField('code', 'default');
        $channel = $this->channelRepository->first();
        $locale = $this->localeRepository->findOneByField('code', 'en');
        $rootCategory = $this->categoryRepository->findOneWhere(['parent_id' => null]);

        // Create Categories
        $this->command->info('Creating perfume categories...');
        
        $mensCategoryData = [
            'locale' => 'en',
            'name' => "Men's Perfumes",
            'slug' => 'mens-perfumes',
            'description' => 'Sophisticated fragrances for modern men',
            'meta_title' => "Men's Perfumes",
            'meta_description' => 'Shop luxury perfumes for men',
            'meta_keywords' => 'mens perfume, cologne, fragrance',
            'display_mode' => 'products_only',
            'status' => 1,
            'parent_id' => $rootCategory->id,
        ];

        $mensCategory = $this->categoryRepository->create($mensCategoryData);

        $womensCategoryData = [
            'locale' => 'en',
            'name' => "Women's Perfumes",
            'slug' => 'womens-perfumes',
            'description' => 'Elegant and luxurious fragrances for women',
            'meta_title' => "Women's Perfumes",
            'meta_description' => 'Shop luxury perfumes for women',
            'meta_keywords' => 'womens perfume, fragrance',
            'display_mode' => 'products_only',
            'status' => 1,
            'parent_id' => $rootCategory->id,
        ];

        $womensCategory = $this->categoryRepository->create($womensCategoryData);

        $unisexCategoryData = [
            'locale' => 'en',
            'name' => 'Unisex Fragrances',
            'slug' => 'unisex-fragrances',
            'description' => 'Gender-neutral scents for everyone',
            'meta_title' => 'Unisex Fragrances',
            'meta_description' => 'Shop unisex perfumes',
            'meta_keywords' => 'unisex perfume, fragrance',
            'display_mode' => 'products_only',
            'status' => 1,
            'parent_id' => $rootCategory->id,
        ];

        $unisexCategory = $this->categoryRepository->create($unisexCategoryData);

        $luxuryCategoryData = [
            'locale' => 'en',
            'name' => 'Luxury Collection',
            'slug' => 'luxury-collection',
            'description' => 'Premium and exclusive fragrances',
            'meta_title' => 'Luxury Collection',
            'meta_description' => 'Shop luxury premium perfumes',
            'meta_keywords' => 'luxury perfume, premium fragrance',
            'display_mode' => 'products_only',
            'status' => 1,
            'parent_id' => $rootCategory->id,
        ];

        $luxuryCategory = $this->categoryRepository->create($luxuryCategoryData);

        $this->command->info('Categories created successfully!');
        $this->command->info('Creating perfume products...');

        // Perfumes Data
        $perfumes = [
            // Men's Perfumes
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-M001',
                'name' => 'Midnight Oud - Luxury Men Cologne',
                'url_key' => 'midnight-oud-luxury-men-cologne',
                'short_description' => 'Luxurious oud-based cologne for the modern gentleman',
                'description' => 'A rich and sophisticated blend of oud wood, amber, and spices. Perfect for evening wear and special occasions. Long-lasting fragrance with notes of leather and sandalwood.',
                'price' => 129.99,
                'special_price' => 99.99,
                'weight' => 300,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Midnight Oud - Premium Men Cologne',
                'meta_keywords' => 'oud, mens cologne, luxury perfume',
                'meta_description' => 'Experience luxury with Midnight Oud cologne',
                'categories' => [$mensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 50],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-M002',
                'name' => 'Ocean Breeze - Fresh Men EDT',
                'url_key' => 'ocean-breeze-fresh-men-edt',
                'short_description' => 'Fresh aquatic fragrance for everyday confidence',
                'description' => 'Light and refreshing aquatic fragrance with marine notes, bergamot, and musk. Perfect for daily wear and casual occasions.',
                'price' => 79.99,
                'special_price' => 59.99,
                'weight' => 250,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Ocean Breeze - Fresh Men Cologne',
                'meta_keywords' => 'fresh, aquatic, mens perfume',
                'meta_description' => 'Stay fresh all day with Ocean Breeze',
                'categories' => [$mensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 75],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-M003',
                'name' => 'Executive Black - Men EDP',
                'url_key' => 'executive-black-men-edp',
                'short_description' => 'Powerful fragrance for business and beyond',
                'description' => 'Bold and powerful fragrance with notes of black pepper, vetiver, and tobacco. Designed for the confident executive.',
                'price' => 149.99,
                'weight' => 300,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 0,
                'meta_title' => 'Executive Black - Premium Men Parfum',
                'meta_keywords' => 'executive, mens perfume, luxury',
                'meta_description' => 'Command attention with Executive Black',
                'categories' => [$mensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 40],
            ],

            // Women's Perfumes
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-W001',
                'name' => 'Rose Elegance - Women EDP',
                'url_key' => 'rose-elegance-women-edp',
                'short_description' => 'Timeless rose fragrance for elegant women',
                'description' => 'Timeless floral fragrance featuring Bulgarian rose, jasmine, and vanilla. Elegant and feminine, perfect for any occasion.',
                'price' => 119.99,
                'special_price' => 89.99,
                'weight' => 250,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Rose Elegance - Luxury Women Perfume',
                'meta_keywords' => 'rose, womens perfume, floral',
                'meta_description' => 'Embrace elegance with Rose Elegance perfume',
                'categories' => [$womensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 60],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-W002',
                'name' => 'Velvet Orchid - Women Luxury',
                'url_key' => 'velvet-orchid-women-luxury',
                'short_description' => 'Exotic orchid perfume with mysterious allure',
                'description' => 'Exotic and sensual fragrance with orchid, black truffle, and honey. A mysterious and captivating scent for the confident woman.',
                'price' => 189.99,
                'special_price' => 159.99,
                'weight' => 300,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Velvet Orchid - Exotic Women Parfum',
                'meta_keywords' => 'orchid, womens perfume, luxury',
                'meta_description' => 'Captivate with Velvet Orchid luxury perfume',
                'categories' => [$womensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 35],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-W003',
                'name' => 'Citrus Blossom - Fresh Women',
                'url_key' => 'citrus-blossom-fresh-women',
                'short_description' => 'Refreshing citrus fragrance for daytime wear',
                'description' => 'Light and refreshing citrus fragrance with orange blossom, grapefruit, and white musk. Perfect for spring and summer.',
                'price' => 69.99,
                'special_price' => 49.99,
                'weight' => 200,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 0,
                'meta_title' => 'Citrus Blossom - Fresh Women Perfume',
                'meta_keywords' => 'citrus, fresh, womens perfume',
                'meta_description' => 'Feel fresh with Citrus Blossom perfume',
                'categories' => [$womensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 100],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-W004',
                'name' => 'Diamond Dreams - Women Luxury',
                'url_key' => 'diamond-dreams-women-luxury',
                'short_description' => 'Luxurious fragrance with diamond orchid',
                'description' => 'Sophisticated floral-woody fragrance with diamond orchid, cashmere wood, and amber. A luxurious scent for special occasions.',
                'price' => 229.99,
                'weight' => 300,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Diamond Dreams - Premium Women Parfum',
                'meta_keywords' => 'luxury, womens perfume, premium',
                'meta_description' => 'Luxury redefined with Diamond Dreams',
                'categories' => [$womensCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 25],
            ],

            // Unisex
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-U001',
                'name' => 'Mystic Woods - Unisex EDP',
                'url_key' => 'mystic-woods-unisex-edp',
                'short_description' => 'Earthy unisex fragrance for nature lovers',
                'description' => 'Earthy and mysterious fragrance with cedar, moss, and amber. Perfect for nature lovers. A scent that transcends gender.',
                'price' => 99.99,
                'special_price' => 79.99,
                'weight' => 250,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Mystic Woods - Unisex Nature Parfum',
                'meta_keywords' => 'unisex, woody, nature perfume',
                'meta_description' => 'Connect with nature through Mystic Woods',
                'categories' => [$unisexCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 55],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-U002',
                'name' => 'Vanilla Noir - Unisex Luxury',
                'url_key' => 'vanilla-noir-unisex-luxury',
                'short_description' => 'Warm vanilla fragrance with chocolate notes',
                'description' => 'Rich vanilla combined with dark chocolate and coffee notes. Warm and comforting for all occasions. Sophisticated and addictive.',
                'price' => 139.99,
                'weight' => 300,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 0,
                'meta_title' => 'Vanilla Noir - Luxury Unisex Perfume',
                'meta_keywords' => 'vanilla, unisex, luxury perfume',
                'meta_description' => 'Indulge in Vanilla Noir luxury fragrance',
                'categories' => [$unisexCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 45],
            ],

            // Luxury Collection
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-L001',
                'name' => 'Gold Reserve - Limited Edition',
                'url_key' => 'gold-reserve-limited-edition',
                'short_description' => 'Limited edition luxury parfum with gold',
                'description' => 'Exclusive blend of saffron, agarwood, and 24k gold flakes. Limited edition luxury parfum with only 100 bottles produced.',
                'price' => 399.99,
                'special_price' => 349.99,
                'weight' => 350,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Gold Reserve - Limited Edition Luxury',
                'meta_keywords' => 'luxury, limited edition, gold perfume',
                'meta_description' => 'Exclusive Gold Reserve limited parfum',
                'categories' => [$luxuryCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 15],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-L002',
                'name' => 'Platinum Empire - Ultra Premium',
                'url_key' => 'platinum-empire-ultra-premium',
                'short_description' => 'Ultra-premium handcrafted luxury parfum',
                'description' => 'The pinnacle of luxury with rare ingredients including platinum orchid, white truffle, and diamond dust infusion. Handcrafted masterpiece.',
                'price' => 599.99,
                'weight' => 400,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Platinum Empire - Ultimate Luxury Parfum',
                'meta_keywords' => 'platinum, ultra luxury, premium perfume',
                'meta_description' => 'Experience Platinum Empire ultra-luxury',
                'categories' => [$luxuryCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 10],
            ],
            [
                'type' => 'simple',
                'attribute_family_id' => $attributeFamily->id,
                'sku' => 'PERF-L003',
                'name' => 'Royal Amber - Prestige Collection',
                'url_key' => 'royal-amber-prestige-collection',
                'short_description' => 'Royal fragrance for the discerning elite',
                'description' => 'Majestic blend of royal amber, French lavender, and sandalwood. Crafted for royalty and luxury enthusiasts. Timeless sophistication.',
                'price' => 299.99,
                'special_price' => 259.99,
                'weight' => 300,
                'status' => 1,
                'visible_individually' => 1,
                'guest_checkout' => 1,
                'new' => 1,
                'featured' => 1,
                'meta_title' => 'Royal Amber - Prestige Luxury Parfum',
                'meta_keywords' => 'royal, amber, prestige perfume',
                'meta_description' => 'Luxury fit for royalty - Royal Amber',
                'categories' => [$luxuryCategory->id],
                'channels' => [$channel->id],
                'locale' => 'en',
                'inventories' => [1 => 20],
            ],
        ];

        foreach ($perfumes as $perfumeData) {
            try {
                $product = $this->productRepository->create($perfumeData);
                $this->command->info('✓ Created: ' . $perfumeData['name']);
            } catch (\Exception $e) {
                $this->command->error('✗ Failed to create: ' . $perfumeData['name']);
                $this->command->error('Error: ' . $e->getMessage());
            }
        }

        $this->command->info('Successfully created ' . count($perfumes) . ' perfume products!');
    }
}
