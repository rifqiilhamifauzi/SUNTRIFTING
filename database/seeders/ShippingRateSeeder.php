<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            [
                'province' => 'DKI Jakarta',
                'city' => null,
                'cost' => 10000,
                'estimated_days_min' => 1,
                'estimated_days_max' => 2,
            ],
            [
                'province' => 'Jawa Barat',
                'city' => null,
                'cost' => 12000,
                'estimated_days_min' => 1,
                'estimated_days_max' => 3,
            ],
            [
                'province' => 'Banten',
                'city' => null,
                'cost' => 11000,
                'estimated_days_min' => 1,
                'estimated_days_max' => 3,
            ],
            [
                'province' => 'Jawa Tengah',
                'city' => null,
                'cost' => 16000,
                'estimated_days_min' => 2,
                'estimated_days_max' => 4,
            ],
            [
                'province' => 'DI Yogyakarta',
                'city' => null,
                'cost' => 16000,
                'estimated_days_min' => 2,
                'estimated_days_max' => 4,
            ],
            [
                'province' => 'Jawa Timur',
                'city' => null,
                'cost' => 18000,
                'estimated_days_min' => 2,
                'estimated_days_max' => 4,
            ],
            [
                'province' => 'Bali',
                'city' => null,
                'cost' => 24000,
                'estimated_days_min' => 3,
                'estimated_days_max' => 5,
            ],
            [
                'province' => 'Sumatera Utara',
                'city' => null,
                'cost' => 32000,
                'estimated_days_min' => 3,
                'estimated_days_max' => 5,
            ],
            [
                'province' => 'Sulawesi Selatan',
                'city' => null,
                'cost' => 38000,
                'estimated_days_min' => 4,
                'estimated_days_max' => 6,
            ],
            [
                'province' => 'Lainnya (Luar Jawa)',
                'city' => null,
                'cost' => 35000,
                'estimated_days_min' => 3,
                'estimated_days_max' => 7,
            ],
        ];

        foreach ($rates as $rate) {
            ShippingRate::updateOrCreate(['province' => $rate['province']], $rate);
        }
    }
}

