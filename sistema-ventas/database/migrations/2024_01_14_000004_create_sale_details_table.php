<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('sale_id');
            $table->uuid('product_id');

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 12, 2);

            //  Relación 
            $table->foreign('sale_id')
                ->references('id')
                ->on('sales')           
                ->onUpdate('cascade')
                ->onDelete('cascade');

            //  Relación 
            $table->foreign('product_id')
                ->references('id')
                ->on('products')        
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Auditoría básica
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
}
