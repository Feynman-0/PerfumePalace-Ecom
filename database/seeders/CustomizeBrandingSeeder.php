<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomizeBrandingSeeder extends Seeder
{
    public function run()
    {
        echo "🎨 Customizing Perfume Palace branding...\n\n";
        
        // Update channel configuration
        DB::table('channels')->where('code', 'default')->update([
            'theme' => 'default',
            'hostname' => 'localhost:8000',
            'logo' => 'themes/shop/default/build/assets/perfume-palace-logo.svg',
            'favicon' => 'themes/shop/default/build/assets/perfume-palace-favicon.png',
            'home_seo' => json_encode([
                'meta_title' => 'Perfume Palace - Luxury Fragrances',
                'meta_description' => 'Shop premium perfumes and luxury fragrances for men and women',
                'meta_keywords' => 'perfume, fragrance, cologne, luxury scents'
            ]),
            'updated_at' => now()
        ]);
        
        // Update channel translations
        DB::table('channel_translations')->where('channel_id', 1)->update([
            'name' => 'Perfume Palace',
            'description' => 'Luxury Fragrances & Exquisite Scents',
            'home_page_content' => '<h1>Welcome to Perfume Palace</h1><p>Discover luxury fragrances</p>',
            'footer_content' => '<p>© 2026 Perfume Palace - Premium Fragrances</p>',
            'maintenance_mode_text' => 'Perfume Palace is under maintenance',
            'updated_at' => now()
        ]);
        
        echo "✓ Updated channel branding\n";
        
        // Update locale
        DB::table('locales')->where('code', 'en')->update([
            'name' => 'English',
            'direction' => 'ltr'
        ]);
        
        // Update core config values to remove demo product references
        $configs = [
            'general.general.general.store_name' => 'Perfume Palace',
            'general.content.shop.name' => 'Perfume Palace',
            'general.content.shop.title' => 'Perfume Palace - Luxury Fragrances',
            'general.content.shop.description' => 'Shop premium perfumes and luxury fragrances for men and women',
            'general.content.shop.keywords' => 'perfume, fragrance, cologne, luxury scents, premium perfumes',
            'emails.general.notifications.emails.general.notifications.email-settings.sender-name' => 'Perfume Palace',
            'emails.general.notifications.emails.general.notifications.email-settings.shop-name' => 'Perfume Palace',
        ];
        
        foreach ($configs as $code => $value) {
            DB::table('core_config')->updateOrInsert(
                ['code' => $code, 'channel_code' => 'default', 'locale_code' => 'en'],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }
        
        echo "✓ Updated configuration values\n";
        
        // Update CMS pages to remove demo content
        DB::table('cms_pages')->where('url_key', 'about-us')->update([
            'html_content' => '<h1>About Perfume Palace</h1><p>Welcome to Perfume Palace, your premier destination for luxury fragrances and exquisite scents. We curate the finest perfumes from world-renowned brands.</p>',
            'meta_title' => 'About Perfume Palace',
            'meta_description' => 'Learn about Perfume Palace, your luxury fragrance destination',
            'updated_at' => now()
        ]);
        
        DB::table('cms_pages')->where('url_key', 'customer-service')->update([
            'html_content' => '<h1>Customer Service</h1><p>At Perfume Palace, customer satisfaction is our priority. Contact us for any assistance with your luxury fragrance purchase.</p>',
            'meta_title' => 'Customer Service - Perfume Palace',
            'updated_at' => now()
        ]);
        
        DB::table('cms_pages')->where('url_key', 'return-policy')->update([
            'html_content' => '<h1>Return Policy</h1><p>Perfume Palace offers hassle-free returns within 30 days of purchase. All fragrances must be unopened and in original condition.</p>',
            'meta_title' => 'Return Policy - Perfume Palace',
            'updated_at' => now()
        ]);
        
        DB::table('cms_pages')->where('url_key', 'terms-conditions')->update([
            'html_content' => '<h1>Terms & Conditions</h1><p>By shopping at Perfume Palace, you agree to our terms of service. All products are 100% authentic luxury fragrances.</p>',
            'meta_title' => 'Terms & Conditions - Perfume Palace',
            'updated_at' => now()
        ]);
        
        DB::table('cms_pages')->where('url_key', 'privacy-policy')->update([
            'html_content' => '<h1>Privacy Policy</h1><p>Perfume Palace respects your privacy. We collect only necessary information to process your orders and provide excellent service.</p>',
            'meta_title' => 'Privacy Policy - Perfume Palace',
            'updated_at' => now()
        ]);
        
        echo "✓ Updated CMS pages\n";
        
        // Update sliders/banners to remove demo content
        DB::table('sliders')->update([
            'title' => 'Perfume Palace Banner',
            'updated_at' => now()
        ]);
        
        echo "✓ Updated sliders\n";
        
        // Clear any demo product references in theme customization
        DB::table('theme_customizations')->where('type', 'product_carousel')->update([
            'name' => 'Featured Perfumes',
            'updated_at' => now()
        ]);
        
        DB::table('theme_customizations')->where('type', 'static_content')->update([
            'name' => 'About Perfume Palace',
            'updated_at' => now()
        ]);
        
        echo "✓ Updated theme customizations\n";
        
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "✅ Branding customization complete!\n";
        echo str_repeat('=', 60) . "\n";
        echo "All 'demo product' references removed\n";
        echo "Site now branded as 'Perfume Palace'\n";
        echo str_repeat('=', 60) . "\n";
    }
}
