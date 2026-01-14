<?php

namespace App\Modules\Sales\Repository;

use App\Modules\Sales\Entity\Sale;
use App\Modules\Sales\Entity\SaleDetail;
use App\Modules\Sales\Entity\SaleStatus;
use Illuminate\Database\DatabaseManager;

/**
 * Clase SaleRepository
 *
 * Repositorio para gestionar las operaciones de base de datos relacionadas con ventas.
 * Implementa el patrón Repository para abstractar el acceso a datos.
 *
 * @package App\Modules\Sales\Repository
 */
class SaleRepository
{
    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * Constructor de SaleRepository.
     *
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Crea una nueva venta.
     *
     * @param array $saleData
     * @return Sale
     * @throws \Exception
     */
    public function createSale(array $saleData): Sale
    {
        return Sale::create($saleData);
    }

    /**
     * Obtiene una venta por su ID.
     *
     * @param string $saleId
     * @return Sale|null
     */
    public function getSaleById(string $saleId): ?Sale
    {
        return Sale::find($saleId);
    }

    /**
     * Actualiza una venta existente.
     *
     * @param string $saleId
     * @param array $saleData
     * @return bool
     */
    public function updateSale(string $saleId, array $saleData): bool
    {
        $sale = Sale::find($saleId);
        if (!$sale) {
            return false;
        }
        return $sale->update($saleData);
    }

    /**
     * Elimina una venta.
     *
     * @param string $saleId
     * @return bool
     */
    public function deleteSale(string $saleId): bool
    {
        $sale = Sale::find($saleId);
        if (!$sale) {
            return false;
        }
        return $sale->delete();
    }

    /**
     * Obtiene todas las ventas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSales()
    {
        return Sale::all();
    }

    /**
     * Crea un detalle de venta.
     *
     * @param array $saleDetailData
     * @return SaleDetail
     */
    public function createSaleDetail(array $saleDetailData): SaleDetail
    {
        return SaleDetail::create($saleDetailData);
    }

    /**
     * Obtiene un estado de venta por su ID.
     *
     * @param string $saleStatusId
     * @return SaleStatus|null
     */
    public function getSaleStatusById(string $saleStatusId): ?SaleStatus
    {
        return SaleStatus::find($saleStatusId);
    }
}