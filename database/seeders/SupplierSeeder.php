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
                'name' => 'Madinah Vintage & Thrifting',
                'phone' => '081324567811',
                'email' => 'madinah.vintage@gmail.com',
                'address' => 'Jl. Trunojoyo No. 12, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat',
                'bank_name' => 'BCA',
                'bank_account_number' => '7770192831',
                'bank_account_holder' => 'Fatimah Az-Zahra',
                'status' => 'active',
                'notes' => 'Spesialis abaya, gamis linen, outer muslimah, dan dress syar\'i terkurasi import & lokal.',
            ],
            [
                'name' => 'Al-Bayan Muslim Wardrobe',
                'phone' => '081239871122',
                'email' => 'albayan.wardrobe@gmail.com',
                'address' => 'Jl. Kaliurang KM 5.5 No. 8, Depok, Sleman, DI Yogyakarta',
                'bank_name' => 'Bank Syariah Indonesia (BSI)',
                'bank_account_number' => '7182938475',
                'bank_account_holder' => 'Ahmad Rabbani',
                'status' => 'active',
                'notes' => 'Kurasi busana muslim pria: baju koko katun madinah, kurta, jubah, dan celana sirwal casual.',
            ],
            [
                'name' => 'Hijab Archive Gallery',
                'phone' => '085711223344',
                'email' => 'hijabarchive.jkt@gmail.com',
                'address' => 'Kawasan Pasar Mayestik Lt. 2 Blok B, Kebayoran Baru, Jakarta Selatan',
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '1370098231456',
                'bank_account_holder' => 'Siti Maryam',
                'status' => 'active',
                'notes' => 'Supplier pashmina ceruty silk, voal motif premium, khimar, dan aksesori muslimah berkualitas.',
            ],
            [
                'name' => 'Sakinah Thrift & Ibadah Store',
                'phone' => '082144556677',
                'email' => 'sakinahtrade.mlg@gmail.com',
                'address' => 'Jl. Soekarno Hatta No. 40, Lowokwaru, Kota Malang, Jawa Timur',
                'bank_name' => 'BRI',
                'bank_account_number' => '312001928371501',
                'bank_account_holder' => 'Muhammad Yusuf',
                'status' => 'active',
                'notes' => 'Spesialis perlengkapan salat: mukena silk premium, sajadah Turkey, tasbih, dan sarung tenun.',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(['name' => $supplier['name']], $supplier);
        }
    }
}
