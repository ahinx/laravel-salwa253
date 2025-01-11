<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductPhoto;
use App\Models\ProductSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Product::factory(30)->create()->each(function ($product) {
            ProductSize::factory(rand(1, 3))->create(['product_id' => $product->id]);
            ProductColor::factory(rand(1, 3))->create(['product_id' => $product->id]);
            ProductPhoto::factory(rand(1, 5))->create(['product_id' => $product->id]);
        });
    }
}
