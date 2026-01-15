<?php

namespace App\Modules\Sales\DTO;

/**
 * Clase UpdateSaleDTO
 *
 * Objeto de Transferencia de Datos para la actualización de ventas.
 * Este DTO se utiliza exclusivamente para la actualización de ventas existentes y no incluye el ID,
 * ya que este se maneja a través de la URL en el controlador.
 *
 * @package App\Modules\Sales\DTO
 */
class UpdateSaleDTO
{
    /**
     * @var string
     */
    public string $saleStatusId;

    /**
     * Constructor de UpdateSaleDTO.
     *
     * @param string $saleStatusId
     * @throws \InvalidArgumentException
     */
    public function __construct(string $saleStatusId)
    {
        $this->validateInputs($saleStatusId);
        
        $this->saleStatusId = $saleStatusId;
    }

    /**
     * Valida los datos de entrada.
     *
     * @param string $saleStatusId
     * @throws \InvalidArgumentException
     */
    private function validateInputs(string $saleStatusId): void
    {
        if (empty($saleStatusId)) {
            throw new \InvalidArgumentException("El estado de la venta no puede estar vacío.");
        }
    }

    /**
     * Convierte el DTO a un array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'sale_status_id' => $this->saleStatusId
        ];
    }
}