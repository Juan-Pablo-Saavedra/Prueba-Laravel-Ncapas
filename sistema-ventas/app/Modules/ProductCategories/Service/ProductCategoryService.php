<?php

namespace App\Modules\ProductCategories\Service;

use App\Modules\ProductCategories\Repository\ProductCategoryRepository;
use App\Modules\ProductCategories\DTO\CreateProductCategoryDTO;
use App\Modules\ProductCategories\DTO\UpdateProductCategoryDTO;
use Illuminate\Database\DatabaseManager;

/**
 * Clase ProductCategoryService
 *
 * Servicio para gestionar la lógica de negocio relacionada con categorías de productos.
 * Implementa el principio de responsabilidad única (SRP) y el patrón Strategy para el manejo de reglas de negocio.
 *
 * @package App\Modules\ProductCategories\Service
 */
class ProductCategoryService
{
    /**
     * @var ProductCategoryRepository
     */
    private $productCategoryRepository;

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * Constructor de ProductCategoryService.
     *
     * @param ProductCategoryRepository $productCategoryRepository
     * @param DatabaseManager $databaseManager
     */
    public function __construct(ProductCategoryRepository $productCategoryRepository, DatabaseManager $databaseManager)
    {
        $this->productCategoryRepository = $productCategoryRepository;
        $this->databaseManager = $databaseManager;
    }

    /**
     * Crea una nueva categoría de producto.
     *
     * @param CreateProductCategoryDTO $createProductCategoryDTO
     * @return array
     * @throws \Exception
     */
    public function createProductCategory(CreateProductCategoryDTO $createProductCategoryDTO): array
    {
        $productCategoryData = $createProductCategoryDTO->toArray();

        // Validar que el código sea único
        if ($this->productCategoryRepository->existsByCode($productCategoryData['code'])) {
            throw new \Exception("El código de la categoría ya existe.");
        }

        // Crear la categoría de producto
        $productCategory = $this->productCategoryRepository->createProductCategory($productCategoryData);

        // Crear DTO de respuesta
        $productCategoryResponseDTO = new \App\Modules\ProductCategories\DTO\ProductCategoryResponseDTO(
            $productCategory->id,
            $productCategory->code,
            $productCategory->name,
            $productCategory->description
        );

        return [
            'product_category' => $productCategoryResponseDTO->toArray(),
            'message' => 'Categoría de producto creada exitosamente'
        ];
    }

    /**
     * Obtiene una categoría de producto por su ID.
     *
     * @param string $productCategoryId
     * @return array|null
     */
    public function getProductCategoryById(string $productCategoryId): ?array
    {
        $productCategory = $this->productCategoryRepository->getProductCategoryById($productCategoryId);
        if (!$productCategory) {
            return null;
        }
        return $productCategory->toArray();
    }

    /**
     * Obtiene todas las categorías de productos.
     *
     * @return array
     */
    public function getAllProductCategories(): array
    {
        $productCategories = $this->productCategoryRepository->getAllProductCategories();
        return $productCategories->toArray();
    }

    /**
     * Actualiza una categoría de producto existente.
     *
     * @param string $productCategoryId
     * @param UpdateProductCategoryDTO $updateProductCategoryDTO
     * @return bool
     */
    public function updateProductCategory(string $productCategoryId, UpdateProductCategoryDTO $updateProductCategoryDTO): bool
    {
        $productCategoryData = $updateProductCategoryDTO->toArray();

        return $this->productCategoryRepository->updateProductCategory($productCategoryId, $productCategoryData);
    }

    /**
     * Elimina una categoría de producto.
     *
     * @param string $productCategoryId
     * @return bool|string
     */
    public function deleteProductCategory(string $productCategoryId)
    {
        return $this->productCategoryRepository->deleteProductCategory($productCategoryId);
    }

    /**
     * Verifica si una categoría de producto existe por su ID.
     *
     * @param string $productCategoryId
     * @return bool
     */
    public function exists(string $productCategoryId): bool
    {
        return $this->productCategoryRepository->exists($productCategoryId);
    }
}