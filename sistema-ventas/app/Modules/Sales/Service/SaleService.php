<?php

namespace App\Modules\Sales\Service;

use App\Modules\Sales\Repository\SaleRepository;
use App\Modules\Sales\DTO\CreateSaleDTO;
use App\Modules\Sales\DTO\SaleResponseDTO;
use App\Modules\Sales\Entity\SaleStatus;
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

    /**
     * Crear venta
     */
    public function createSale(CreateSaleDTO $createSaleDTO): array
    {
        return DB::transaction(function () use ($createSaleDTO) {

            $saleData = $createSaleDTO->toArray();

            // 🔹 Estado inicial obligatorio
            $status = SaleStatus::where('code', 'PENDING')->firstOrFail();

            // 🔹 Validar productos y stock
            $this->validateProductsAndStock($saleData['sale_details']);

            // 🔹 Calcular total
            $totalAmount = $this->calculateTotalAmount($saleData['sale_details']);

            // 🔹 Crear venta
            $sale = $this->saleRepository->createSale([
                'sale_date' => $saleData['sale_date'],
                'sale_status_id' => $status->id,
                'total_amount' => $totalAmount,
            ]);

            // 🔹 Crear detalles
            foreach ($saleData['sale_details'] as $detail) {
                $this->saleRepository->createSaleDetail([
                    'sale_id' => $sale->id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            $responseDTO = new SaleResponseDTO(
                $sale->id,
                $sale->sale_date,
                $sale->total_amount,
                $sale->sale_status_id,
                $saleData['sale_details']
            );

            return [
                'sale' => $responseDTO->toArray(),
                'message' => 'Venta creada exitosamente'
            ];
        });
    }

    /**
     * Validar productos y stock
     */
    private function validateProductsAndStock(array $saleDetails): void
    {
        foreach ($saleDetails as $detail) {
            $product = $this->productService->getProductById($detail['product_id']);

            if (!$product) {
                throw new \Exception("Producto no encontrado: {$detail['product_id']}");
            }

            if ($product['stock'] < $detail['quantity']) {
                throw new \Exception(
                    "Stock insuficiente para el producto: {$product['name']}"
                );
            }
        }
    }

    /**
     * Calcular total
     */
    private function calculateTotalAmount(array $saleDetails): float
    {
        return array_reduce(
            $saleDetails,
            fn ($total, $item) => $total + $item['subtotal'],
            0
        );
    }

    public function getSaleById(string $saleId): ?array
    {
        $sale = $this->saleRepository->getSaleById($saleId);
        return $sale ? $sale->toArray() : null;
    }

    public function getAllSales(): array
    {
        return $this->saleRepository->getAllSales()->toArray();
    }

    public function updateSale(string $saleId, array $saleData): bool
    {
        return $this->saleRepository->updateSale($saleId, $saleData);
    }

    public function deleteSale(string $saleId): bool
    {
        return $this->saleRepository->deleteSale($saleId);
    }
}
