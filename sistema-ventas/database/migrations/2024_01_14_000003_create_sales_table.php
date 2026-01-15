<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();

            //  columnas normales
            $table->date('sale_date');
            $table->decimal('total_amount', 12, 2);

            //  FK
            $table->uuid('sale_status_id');

            //  FK 
            $table->foreign('sale_status_id')
                ->references('id')
                ->on('sale_statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
}
