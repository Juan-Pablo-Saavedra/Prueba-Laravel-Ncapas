<?php

namespace App\Modules\ProductCategories\Repository;

use App\Modules\ProductCategories\Entity\ProductCategory;
use Illuminate\Database\DatabaseManager;

/**
 * Clase ProductCategoryRepository
 *
 * Repositorio para gestionar las operaciones de base de datos relacionadas con categorías de productos.
 * Implementa el patrón Repository para abstractar el acceso a datos.
 *
 * @package App\Modules\ProductCategories\Repository
 */
class ProductCategoryRepository
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * Constructor de ProductCategoryRepository.
     *
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Crea una nueva categoría de producto.
     *
     * @param array $productCategoryData
     * @return ProductCategory
     */
    public function createProductCategory(array $productCategoryData): ProductCategory
    {
        return ProductCategory::create($productCategoryData);
    }

    /**
     * Obtiene una categoría de producto por su ID.
     *
     * @param string $productCategoryId
     * @return ProductCategory|null
     */
    public function getProductCategoryById(string $productCategoryId): ?ProductCategory
    {
        return ProductCategory::find($productCategoryId);
    }

    /**
     * Obtiene todas las categorías de productos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProductCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return ProductCategory::all();
    }

    /**
     * Actualiza una categoría de producto existente.
     *
     * @param string $productCategoryId
     * @param array $productCategoryData
     * @return bool
     */
    public function updateProductCategory(string $productCategoryId, array $productCategoryData): bool
    {
        $productCategory = ProductCategory::find($productCategoryId);
        if (!$productCategory) {
            return false;
        }
        return $productCategory->update($productCategoryData);
    }

    /**
     * Elimina una categoría de producto.
     *
     * @param string $productCategoryId
     * @return bool|string
     */
    public function deleteProductCategory(string $productCategoryId)
    {
        $productCategory = ProductCategory::find($productCategoryId);
        if (!$productCategory) {
            return false;
        }

        // Verificar si la categoría está asociada a productos
        if ($productCategory->products()->exists()) {
            return 'conflict';
        }

        return $productCategory->delete();
    }

    /**
     * Verifica si una categoría de producto existe por su ID.
     *
     * @param string $productCategoryId
     * @return bool
     */
    public function exists(string $productCategoryId): bool
    {
        return ProductCategory::where('id', $productCategoryId)->exists();
    }

    /**
     * Verifica si una categoría de producto existe por su código.
     *
     * @param string $code
     * @return bool
     */
    public function existsByCode(string $code): bool
    {
        return ProductCategory::where('code', $code)->exists();
    }
}