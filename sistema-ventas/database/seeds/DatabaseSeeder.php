<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ProductCategorySeeder::class,
            SaleStatusSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
