<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        echo "👥 Creating users for Perfume Palace...\n\n";
        
        // Update Admin
        $adminPass = Hash::make('Admin@123');
        DB::table('admins')->where('id', 1)->update([
            'name' => 'Perfume Palace Admin',
            'email' => 'admin@perfumepalace.com',
            'password' => $adminPass,
            'updated_at' => now()
        ]);
        
        echo "✓ Admin User Updated\n";
        echo "  Email: admin@perfumepalace.com\n";
        echo "  Password: Admin@123\n\n";
        
        // Create Customer
        $customerPass = Hash::make('Customer@123');
        
        // Check if customer exists
        $existingCustomer = DB::table('customers')->where('email', 'customer@perfumepalace.com')->first();
        
        if ($existingCustomer) {
            DB::table('customers')->where('email', 'customer@perfumepalace.com')->update([
                'password' => $customerPass,
                'updated_at' => now()
            ]);
            echo "✓ Customer User Updated\n";
        } else {
            DB::table('customers')->insert([
                'first_name' => 'John',
                'last_name' => 'Customer',
                'gender' => 'Male',
                'email' => 'customer@perfumepalace.com',
                'password' => $customerPass,
                'status' => 1,
                'is_verified' => 1,
                'customer_group_id' => 1,
                'channel_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✓ Customer User Created\n";
        }
        
        echo "  Email: customer@perfumepalace.com\n";
        echo "  Password: Customer@123\n\n";
        
        echo "{'='*60}\n";
        echo "✅ Users ready!\n";
        echo "{'='*60}\n";
    }
}
