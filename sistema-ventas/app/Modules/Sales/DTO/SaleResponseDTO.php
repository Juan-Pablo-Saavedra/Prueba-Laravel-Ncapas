<?php

namespace App\Modules\Sales\DTO;

/**
 * Clase SaleResponseDTO
 *
 * Objeto de Transferencia de Datos para la respuesta de ventas.
 * Este DTO se utiliza para devolver información de la venta, incluyendo el ID generado por el sistema.
 *
 * @package App\Modules\Sales\DTO
 */
class SaleResponseDTO
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $saleDate;

    /**
     * @var float
     */
    public float $totalAmount;

    /**
     * @var string
     */
    public string $saleStatusId;

    /**
     * @var array
     */
    public array $saleDetails;

    /**
     * Constructor de SaleResponseDTO.
     *
     * @param string $id
     * @param string $saleDate
     * @param float $totalAmount
     * @param string $saleStatusId
     * @param array $saleDetails
     */
    public function __construct(
        string $id,
        string $saleDate,
        float $totalAmount,
        string $saleStatusId,
        array $saleDetails
    ) {
        $this->id = $id;
        $this->saleDate = $saleDate;
        $this->totalAmount = $totalAmount;
        $this->saleStatusId = $saleStatusId;
        $this->saleDetails = $saleDetails;
    }

    /**
     * Convierte el DTO a un array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sale_date' => $this->saleDate,
            'total_amount' => $this->totalAmount,
            'sale_status_id' => $this->saleStatusId,
            'sale_details' => $this->saleDetails
        ];
    }
}