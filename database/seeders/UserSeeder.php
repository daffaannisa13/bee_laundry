<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 akun admin
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin Utama'
        ]);

        // 14 akun user
        $names = [
            'Daffa', 'Alya', 'Rizky', 'Nadia', 'Fikri',
            'Salsa', 'Bagas', 'Intan', 'Rehan', 'Putri',
            'Iqbal', 'Citra', 'Rafi', 'Nadila'
        ];

        foreach ($names as $i => $name) {
            User::create([
                'name' => $name,
                'email' => strtolower($name).'@mail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '08123'.rand(100000, 999999),
                'address' => 'Alamat '.$name.' No. '.($i+1),
            ]);
        }
    }
}
