<?php

namespace App\Modules\Sales\DTO;

class CreateSaleDTO
{
    public string $saleDate;
    public array $saleDetails;

    public function __construct(string $saleDate, array $saleDetails)
    {
        if (empty($saleDate)) {
            throw new \InvalidArgumentException('La fecha de la venta no puede estar vacía.');
        }

        if (empty($saleDetails)) {
            throw new \InvalidArgumentException('La venta debe tener al menos un detalle.');
        }

        $this->saleDate = $saleDate;
        $this->saleDetails = $saleDetails;
    }

    /**
     * Datos exclusivos de la tabla sales
     */
    public function toSaleArray(): array
    {
        return [
            'sale_date' => $this->saleDate,
        ];
    }

    /**
     * Detalles de la venta (sale_details)
     */
    public function getDetails(): array
    {
        return $this->saleDetails;
    }
}
