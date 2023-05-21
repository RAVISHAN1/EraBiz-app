<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description for Product 1',
                'price' => 1999.99,
                'image_url' => '',
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for Product 2',
                'price' => 2599.99,
                'image_url' => '',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
