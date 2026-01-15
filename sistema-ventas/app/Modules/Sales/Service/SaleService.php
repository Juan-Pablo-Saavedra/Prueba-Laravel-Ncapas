<?php

namespace App\Modules\Sales\Service;

use App\Modules\Sales\Repository\SaleRepository;
use App\Modules\Sales\DTO\CreateSaleDTO;
use App\Modules\Sales\DTO\SaleResponseDTO;
use App\Modules\Products\Service\ProductService;
use Illuminate\Support\Facades\DB;

class SaleService
{
    private SaleRepository $saleRepository;
    private ProductService $productService;

    public function __construct(
        SaleRepository $saleRepository,
        ProductService $productService
    ) {
        $this->saleRepository = $saleRepository;
        $this->productService = $productService;
    }

    public function createSale(CreateSaleDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {

            // 1️⃣ Obtener estado PENDING (dato maestro)
            $pendingStatus = $this->saleRepository->getSaleStatusByCode('PENDING');

            if (!$pendingStatus) {
                throw new \DomainException('Estado de venta PENDING no configurado');
            }

            // 2️⃣ Validar productos + stock
            $total = 0;

            foreach ($dto->getDetails() as $detail) {
                $product = $this->productService->getProductById($detail['product_id']);

                if (!$product) {
                    throw new \DomainException("Producto no encontrado: {$detail['product_id']}");
                }

                if ($product['stock'] < $detail['quantity']) {
                    throw new \DomainException("Stock insuficiente para {$product['name']}");
                }

                $total += $detail['subtotal'];
            }

            // 3️⃣ Crear venta
            $sale = $this->saleRepository->createSale([
                'sale_date'       => $dto->saleDate,
                'sale_status_id'  => $pendingStatus->id,
                'total_amount'    => $total,
            ]);

            // 4️⃣ Crear detalles + descontar stock
            foreach ($dto->getDetails() as $detail) {
                $detail['sale_id'] = $sale->id;

                $this->saleRepository->createSaleDetail($detail);
            }

            // 5️⃣ Respuesta
            return [
                'sale' => (new SaleResponseDTO(
                    $sale->id,
                    $sale->sale_date,
                    $sale->total_amount,
                    $sale->sale_status_id,
                    $dto->getDetails()
                ))->toArray(),
                'message' => 'Venta creada exitosamente'
            ];
        });
    }

    public function getSaleById(string $saleId): ?array
    {
        $sale = $this->saleRepository->getSaleById($saleId);

        if (!$sale) {
            return null;
        }

        return (new SaleResponseDTO(
            $sale->id,
            $sale->sale_date,
            $sale->total_amount,
            $sale->sale_status_id,
            $sale->details
        ))->toArray();
    }

    public function getAllSales(): array
    {
        $sales = $this->saleRepository->getAllSales();
        $salesArray = [];

        foreach ($sales as $sale) {
            $salesArray[] = (new SaleResponseDTO(
                $sale->id,
                $sale->sale_date,
                $sale->total_amount,
                $sale->sale_status_id,
                $sale->details
            ))->toArray();
        }

        return $salesArray;
    }

    public function updateSaleStatus(string $saleId, string $saleStatusCode): bool
    {
        $sale = $this->saleRepository->getSaleById($saleId);

        if (!$sale) {
            return false;
        }

        $saleStatus = $this->saleRepository->getSaleStatusByCode($saleStatusCode);

        if (!$saleStatus) {
            throw new \DomainException("Estado de venta no encontrado: {$saleStatusCode}");
        }

        return $this->saleRepository->updateSale($saleId, ['sale_status_id' => $saleStatus->id]);
    }

    public function deleteSale(string $saleId): bool
    {
        return $this->saleRepository->deleteSale($saleId);
    }
}
