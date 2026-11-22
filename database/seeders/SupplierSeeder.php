<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Vintage Vault Bandung',
                'phone' => '081324567811',
                'email' => 'vintagevault.bdg@gmail.com',
                'address' => 'Jl. Trunojoyo No. 12, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat',
                'bank_name' => 'BCA',
                'bank_account_number' => '7770192831',
                'bank_account_holder' => 'Dimas Arya Pratama',
                'status' => 'active',
                'notes' => 'Spesialis jaket denim & workwear Carhartt / Dickies.',
            ],
            [
                'name' => 'Retro Syndicate Jogja',
                'phone' => '081239871122',
                'email' => 'retrosyndicate@gmail.com',
                'address' => 'Jl. Kaliurang KM 5.5 No. 8, Depok, Sleman, DI Yogyakarta',
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '1370098231456',
                'bank_account_holder' => 'Anindya Putri',
                'status' => 'active',
                'notes' => 'Supplier kaos band vintage, rap tee, dan anime tee original 90s.',
            ],
            [
                'name' => 'Second Street Pasar Senen',
                'phone' => '085711223344',
                'email' => 'secondstreet.senen@gmail.com',
                'address' => 'Kawasan Pasar Senen Blok 3 Lt. 2, Senen, Jakarta Pusat',
                'bank_name' => 'BNI',
                'bank_account_number' => '0823419082',
                'bank_account_holder' => 'Bambang Supriyanto',
                'status' => 'active',
                'notes' => 'Supplier partai besar hoodie Champion, Nike, Stussy curated.',
            ],
            [
                'name' => 'Thrift Station Malang',
                'phone' => '082144556677',
                'email' => 'thriftstation.mlg@gmail.com',
                'address' => 'Jl. Soekarno Hatta No. 40, Lowokwaru, Kota Malang, Jawa Timur',
                'bank_name' => 'BRI',
                'bank_account_number' => '312001928371501',
                'bank_account_holder' => 'Fajar Nugroho',
                'status' => 'active',
                'notes' => 'Kolektor celana Levi’s Made in USA 501 & 505.',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(['name' => $supplier['name']], $supplier);
        }
    }
}

