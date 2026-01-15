<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleStatusSeeder extends Seeder
{
    public function run()
    {
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
    }
}
