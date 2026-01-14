<?php

namespace App\Modules\Products\Service;

use App\Modules\Products\Repository\ProductRepository;
use App\Modules\Products\DTO\CreateProductDTO;
use App\Modules\Products\DTO\UpdateProductDTO;
use App\Modules\Products\DTO\ProductResponseDTO;
use Illuminate\Support\Facades\DB;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct(CreateProductDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {

            $data = $dto->toArray();

            $this->validateProductCategory($data['product_category_id']);

            $product = $this->productRepository->createProduct($data);

            return [
                'product' => (new ProductResponseDTO(
                    $product->id,
                    $product->code,
                    $product->name,
                    $product->description,
                    $product->price,
                    $product->stock,
                    $product->product_category_id
                ))->toArray(),
                'message' => 'Producto creado exitosamente'
            ];
        });
    }

    private function validateProductCategory(string $productCategoryId): void
    {
        if (!$this->productRepository->getProductCategoryById($productCategoryId)) {
            throw new \InvalidArgumentException(
                "Categoría de producto no encontrada: {$productCategoryId}"
            );
        }
    }

    public function getProductById(string $productId): ?array
    {
        $product = $this->productRepository->getProductById($productId);
        return $product ? $product->toArray() : null;
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->getAllProducts()->toArray();
    }

    public function updateProduct(string $productId, UpdateProductDTO $dto): bool
    {
        return $this->productRepository
            ->updateProduct($productId, $dto->toArray());
    }

    public function deleteProduct(string $productId): bool
    {
        return $this->productRepository->deleteProduct($productId);
    }

    public function getProductsByCategory(string $productCategoryId): array
    {
        return $this->productRepository
            ->getProductsByCategory($productCategoryId)
            ->toArray();
    }
}
