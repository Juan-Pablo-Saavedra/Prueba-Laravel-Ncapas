<?php

namespace App\Modules\Products\DTO;

/**
 * Clase ProductResponseDTO
 *
 * Objeto de Transferencia de Datos para la respuesta de productos.
 * Este DTO se utiliza para devolver información del producto, incluyendo el ID generado por el sistema.
 *
 * @package App\Modules\Products\DTO
 */
class ProductResponseDTO
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * @var float
     */
    public float $price;

    /**
     * @var int
     */
    public int $stock;

    /**
     * @var string
     */
    public string $productCategoryId;

    /**
     * Constructor de ProductResponseDTO.
     *
     * @param string $id
     * @param string $code
     * @param string $name
     * @param string|null $description
     * @param float $price
     * @param int $stock
     * @param string $productCategoryId
     */
    public function __construct(
        string $id,
        string $code,
        string $name,
        ?string $description,
        float $price,
        int $stock,
        string $productCategoryId
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->productCategoryId = $productCategoryId;
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'product_category_id' => $this->productCategoryId
        ];
    }
}