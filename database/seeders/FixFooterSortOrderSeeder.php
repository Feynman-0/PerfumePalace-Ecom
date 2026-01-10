<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixFooterSortOrderSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔧 Fixing footer links sort_order...');

        // Get the footer links customization
        $footerCustomization = DB::table('theme_customization_translations')
            ->where('theme_customization_id', 11)
            ->first();

        if ($footerCustomization) {
            $options = json_decode($footerCustomization->options, true);

            // Add sort_order to each link
            foreach ($options as $columnKey => &$links) {
                $sortOrder = 1;
                foreach ($links as &$link) {
                    $link['sort_order'] = $sortOrder++;
                }
            }

            // Update the database
            DB::table('theme_customization_translations')
                ->where('theme_customization_id', 11)
                ->update([
                    'options' => json_encode($options)
                ]);

            $this->command->info('✅ Footer links sort_order fixed!');
        }

        // Also fix services content if it exists
        $servicesCustomization = DB::table('theme_customization_translations')
            ->where('theme_customization_id', 12)
            ->first();

        if ($servicesCustomization) {
            $options = json_decode($servicesCustomization->options, true);

            if (isset($options['services']) && is_array($options['services'])) {
                $sortOrder = 1;
                foreach ($options['services'] as &$service) {
                    if (!isset($service['sort_order'])) {
                        $service['sort_order'] = $sortOrder++;
                    }
                }

                DB::table('theme_customization_translations')
                    ->where('theme_customization_id', 12)
                    ->update([
                        'options' => json_encode($options)
                    ]);

                $this->command->info('✅ Services content sort_order fixed!');
            }
        }

        $this->command->info('🎉 All sort_order issues resolved!');
    }
}
