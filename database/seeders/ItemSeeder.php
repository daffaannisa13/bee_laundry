<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
    [
        'nama_service' => 'Clothes (Wash & Iron)',
        'harga' => 15000,
        'tipe_service' => 'Wash & Iron',
        'deskripsi' => 'Cuci pakaian sehari-hari dan disetrika',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'nama_service' => 'Clothes (Dry Cleaning)',
        'harga' => 10000,
        'tipe_service' => 'Dry Cleaning',
        'deskripsi' => 'Hanya cuci kering tanpa setrika',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'nama_service' => 'Blanket',
        'harga' => 25000,
        'tipe_service' => 'Wash & Iron',
        'deskripsi' => 'Cuci selimut besar dan disetrika',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'nama_service' => 'Bedcover',
        'harga' => 30000,
        'tipe_service' => 'Wash & Iron',
        'deskripsi' => 'Cuci bedcover ukuran standar dan disetrika',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'nama_service' => 'Shoes',
        'harga' => 20000,
        'tipe_service' => 'Wash & Dry',
        'deskripsi' => 'Cuci sepatu kain/karet, tidak disetrika',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];


        DB::table('items')->insert($items);
    }
}
