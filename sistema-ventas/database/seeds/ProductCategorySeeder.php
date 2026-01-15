<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('product_categories')->insert([
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
                'code' => 'ELECTRONICS',
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
