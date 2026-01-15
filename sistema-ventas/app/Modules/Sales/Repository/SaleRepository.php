<?php

namespace App\Modules\Sales\Repository;

use App\Modules\Sales\Entity\Sale;
use App\Modules\Sales\Entity\SaleDetail;
use App\Modules\Sales\Entity\SaleStatus;

/**
 * Clase SaleRepository
 *
 * Repositorio para gestionar las operaciones de base de datos relacionadas con ventas.
 *
 * @package App\Modules\Sales\Repository
 */
class SaleRepository
{
    /**
     * Crea una nueva venta.
     *
     * @param array $saleData
     * @return Sale
     */
    public function createSale(array $saleData): Sale
    {
        return Sale::create($saleData);
    }

    /**
     * Obtiene una venta por su ID.
     */
    public function getSaleById(string $saleId): ?Sale
    {
        return Sale::find($saleId);
    }

    /**
     * Actualiza una venta existente.
     */
    public function updateSale(string $saleId, array $saleData): bool
    {
        $sale = Sale::find($saleId);

        return $sale ? $sale->update($saleData) : false;
    }

    /**
     * Elimina una venta.
     */
    public function deleteSale(string $saleId): bool
    {
        $sale = Sale::find($saleId);

        return $sale ? $sale->delete() : false;
    }

    /**
     * Obtiene todas las ventas.
     */
    public function getAllSales()
    {
        return Sale::all();
    }

    /**
     * Crea un detalle de venta.
     */
    public function createSaleDetail(array $saleDetailData): SaleDetail
    {
        return SaleDetail::create($saleDetailData);
    }

    /**
     * Obtiene un estado de venta por ID.
     */
    public function getSaleStatusById(string $saleStatusId): ?SaleStatus
    {
        return SaleStatus::find($saleStatusId);
    }

    /**
     * Obtiene un estado de venta por código (PENDING, COMPLETED, etc).
     */
    public function getSaleStatusByCode(string $code): ?SaleStatus
    {
        return SaleStatus::where('code', $code)->first();
    }
}
