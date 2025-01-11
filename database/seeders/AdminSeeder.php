<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Mila',
            'email' => 'mila@gmail.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Latif',
            'email' => 'latif@gmail.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Topan',
            'email' => 'topan@gmail.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Lekho',
            'email' => 'lekho@gmail.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Merisa',
            'email' => 'merisa@gmail.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kokom',
            'email' => 'kokom@gmail.com',
            // 'username' => 'admin', // Tambahkan kolom username di User jika belum ada
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);
    }
}
