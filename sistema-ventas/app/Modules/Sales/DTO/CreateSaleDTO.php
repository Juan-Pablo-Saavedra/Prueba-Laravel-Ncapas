<?php

namespace App\Modules\Sales\DTO;

/**
 * Clase CreateSaleDTO
 *
 * Objeto de Transferencia de Datos para la creación de ventas.
 * Este DTO se utiliza exclusivamente para la creación de nuevas ventas y no incluye el ID,
 * ya que este es generado por el sistema.
 *
 * @package App\Modules\Sales\DTO
 */
class CreateSaleDTO
{
    public string $saleDate;
    public array $saleDetails;

    public function __construct(string $saleDate, array $saleDetails)
    {
        if (empty($saleDate)) {
            throw new \InvalidArgumentException("La fecha de la venta no puede estar vacía.");
        }
        if (empty($saleDetails)) {
            throw new \InvalidArgumentException("La venta debe tener al menos un detalle.");
        }

        $this->saleDate = $saleDate;
        $this->saleDetails = $saleDetails;
    }

    public function toArray(): array
    {
        return [
            'sale_date' => $this->saleDate,
            'sale_details' => $this->saleDetails
        ];
    }
}
