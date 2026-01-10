<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimpleBrandingSeeder extends Seeder
{
    public function run()
    {
        echo "🎨 Customizing Perfume Palace...\n\n";
        
        // Update channel
        DB::table('channels')->where('code', 'default')->update([
            'theme' => 'default',
            'hostname' => 'localhost:8000',
            'logo' => 'themes/shop/default/build/assets/perfume-palace-logo.svg',
            'favicon' => 'themes/shop/default/build/assets/perfume-palace-favicon.png',
            'updated_at' => now()
        ]);
        
        // Update channel translations
        DB::table('channel_translations')->where('channel_id', 1)->where('locale', 'en')->update([
            'name' => 'Perfume Palace',
            'description' => 'Luxury Fragrances & Exquisite Scents',
            'maintenance_mode_text' => 'Perfume Palace - Under Maintenance',
            'updated_at' => now()
        ]);
        
        echo "✓ Channel branding updated\n";
        
        // Update CMS pages
        $cmsUpdates = [
            'about-us' => [
                'html_content' => '<div class="container"><h1>About Perfume Palace</h1><p>Welcome to Perfume Palace, your premier destination for luxury fragrances. We offer authentic perfumes from world-renowned brands.</p></div>',
                'meta_title' => 'About Perfume Palace',
                'meta_description' => 'Learn about Perfume Palace luxury fragrances'
            ],
            'customer-service' => [
                'html_content' => '<div class="container"><h1>Customer Service</h1><p>At Perfume Palace, we prioritize your satisfaction. Contact us for assistance.</p></div>',
                'meta_title' => 'Customer Service - Perfume Palace'
            ],
            'return-policy' => [
                'html_content' => '<div class="container"><h1>Return Policy</h1><p>30-day hassle-free returns on unopened products.</p></div>',
                'meta_title' => 'Return Policy - Perfume Palace'
            ],
            'privacy-policy' => [
                'html_content' => '<div class="container"><h1>Privacy Policy</h1><p>Your privacy matters at Perfume Palace.</p></div>',
                'meta_title' => 'Privacy Policy - Perfume Palace'
            ],
            'terms-conditions' => [
                'html_content' => '<div class="container"><h1>Terms & Conditions</h1><p>All products are 100% authentic.</p></div>',
                'meta_title' => 'Terms - Perfume Palace'
            ]
        ];
        
        foreach ($cmsUpdates as $urlKey => $data) {
            $page = DB::table('cms_pages')->where('url_key', $urlKey)->first();
            if ($page) {
                DB::table('cms_page_translations')
                    ->where('cms_page_id', $page->id)
                    ->where('locale', 'en')
                    ->update(array_merge($data, ['updated_at' => now()]));
            }
        }
        
        echo "✓ CMS pages updated\n";
        
        // Update theme customizations to remove demo references
        DB::table('theme_customizations')->update([
            'updated_at' => now()
        ]);
        
        echo "✓ Theme updated\n";
        
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "✅ Perfume Palace branding complete!\n";
        echo str_repeat('=', 60) . "\n";
    }
}
