<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Faker\Generator as Faker;  // Pastikan Faker diimpor
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(Faker $faker)  // Menambahkan parameter Faker ke method run
    {

        DB::table('settings')->insert([
            'key' => 'item_code_start',
            'value' => '100000',  // Nilai awalan untuk kode item
        ]);

        $this->call([
            // ProductSeeder::class,
            AdminSeeder::class,
        ]);

        // // Seed Supplier terlebih dahulu
        // Supplier::factory(10)->create();

        // // Seed untuk Color dan Size
        // Color::create(['color' => 'Hitam']);
        // Color::create(['color' => 'Hijau']);
        // Color::create(['color' => 'Kuning']);
        // Size::create(['size' => 'XS']);
        // Size::create(['size' => 'S']);
        // Size::create(['size' => 'M']);
        // Size::create(['size' => 'L']);
        // Size::create(['size' => 'XL']);
        // Size::create(['size' => 'XXL']);

        // // Seed untuk Produk dan relasinya
        // Product::factory(30)->create()->each(function ($product) use ($faker) { // Menggunakan Faker dalam closure
        //     // Generate ukuran produk (1-3 ukuran)
        //     $product->sizes()->attach(\App\Models\Size::inRandomOrder()->take(rand(1, 3))->pluck('id'));

        //     // Generate warna produk (1-3 warna)
        //     $product->colors()->attach(\App\Models\Color::inRandomOrder()->take(rand(1, 3))->pluck('id'));

        //     // Generate foto produk (1-5 foto)
        //     $product->photos()->create([
        //         'photo_path' => $faker->imageUrl(640, 480, 'products'),  // Menggunakan $faker yang disuntikkan
        //     ]);
        // });
    }
}
