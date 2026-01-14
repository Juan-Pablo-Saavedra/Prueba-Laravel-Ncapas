<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Modules\Sales\Entity\SaleStatus;

class SaleStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'id' => Str::uuid(),
                'code' => 'PENDING',
                'name' => 'Pendiente',
                'description' => 'Venta creada, pendiente de procesamiento',
            ],
            [
                'id' => Str::uuid(),
                'code' => 'PAID',
                'name' => 'Pagada',
                'description' => 'Venta pagada',
            ],
            [
                'id' => Str::uuid(),
                'code' => 'CANCELLED',
                'name' => 'Cancelada',
                'description' => 'Venta cancelada',
            ],
        ];

        foreach ($statuses as $status) {
            SaleStatus::updateOrCreate(
                ['code' => $status['code']],
                $status
            );
        }
    }
}
