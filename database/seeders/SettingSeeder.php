<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'admin_commission_pct',
                'value' => '10.00',
                'type' => 'number',
                'group' => 'financial',
            ],
            [
                'key' => 'supplier_commission_pct',
                'value' => '90.00',
                'type' => 'number',
                'group' => 'financial',
            ],
            [
                'key' => 'store_name',
                'value' => 'Suntrifting Thrift Consignment',
                'type' => 'string',
                'group' => 'general',
            ],
            [
                'key' => 'store_tagline',
                'value' => 'Curated Preloved & Vintage Streetwear',
                'type' => 'string',
                'group' => 'general',
            ],
            [
                'key' => 'bank_bca_number',
                'value' => '8271-9283-71',
                'type' => 'string',
                'group' => 'bank',
            ],
            [
                'key' => 'bank_bca_holder',
                'value' => 'PT Suntrifting Karya Mandiri',
                'type' => 'string',
                'group' => 'bank',
            ],
            [
                'key' => 'bank_mandiri_number',
                'value' => '137-00-1928371-0',
                'type' => 'string',
                'group' => 'bank',
            ],
            [
                'key' => 'bank_mandiri_holder',
                'value' => 'PT Suntrifting Karya Mandiri',
                'type' => 'string',
                'group' => 'bank',
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '081234567890',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_email',
                'value' => 'halo@suntrifting.com',
                'type' => 'string',
                'group' => 'contact',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

