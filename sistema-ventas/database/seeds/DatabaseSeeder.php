<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Product Categories
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Sale Statuses (DATOS MAESTROS)
        |--------------------------------------------------------------------------
        */
        DB::table('sale_statuses')->insert([
            [
                'id' => '550e8400-e29b-41d4-a716-446655440003',
                'code' => 'PENDING',
                'name' => 'Pending',
                'description' => 'Sale is pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440004',
                'code' => 'COMPLETED',
                'name' => 'Completed',
                'description' => 'Sale is completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */
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
