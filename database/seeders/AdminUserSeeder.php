<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@suntrifting.com'],
            [
                'name' => 'Admin Suntrifting',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // Demo customer user
        $customer = User::updateOrCreate(
            ['email' => 'customer@suntrifting.com'],
            [
                'name' => 'Rifqi Customer',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '089876543210',
                'email_verified_at' => now(),
            ]
        );

        // Ensure customer has cart
        Cart::firstOrCreate(['user_id' => $customer->id]);

        // Create sample address for customer
        Address::firstOrCreate(
            [
                'user_id' => $customer->id,
                'label' => 'Rumah',
            ],
            [
                'recipient_name' => 'Rifqi Customer',
                'phone' => '089876543210',
                'address_line' => 'Jl. Diponegoro No. 45, RT 02 / RW 05, Menteng',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10310',
                'is_default' => true,
            ]
        );
    }
}

