<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'id' => '550e8400-e29b-41d4-a716-446655440005',
                'code' => 'PROD001',
                'name' => 'Laptop',
                'description' => 'A powerful laptop',
                'price' => 999.99,
                'stock' => 10,
                'product_category_id' => '550e8400-e29b-41d4-a716-446655440001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
