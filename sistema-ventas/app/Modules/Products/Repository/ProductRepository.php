<?php

namespace App\Modules\Products\Repository;

use App\Modules\Products\Entity\Product;
use App\Modules\ProductCategories\Entity\ProductCategory;

class ProductRepository
{
    public function createProduct(array $productData): Product
    {
        return Product::create($productData);
    }

    public function getProductById(string $productId): ?Product
    {
        return Product::find($productId);
    }

    public function updateProduct(string $productId, array $productData): bool
    {
        $product = Product::find($productId);
        return $product ? $product->update($productData) : false;
    }

    public function deleteProduct(string $productId): bool
    {
        $product = Product::find($productId);
        return $product ? $product->delete() : false;
    }

    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductCategoryById(string $productCategoryId): ?ProductCategory
    {
        return ProductCategory::find($productCategoryId);
    }

    public function getProductsByCategory(string $productCategoryId)
    {
        return Product::where('product_category_id', $productCategoryId)->get();
    }
}
