<?php

namespace App\Modules\Products\DTO;

/**
 * Clase UpdateProductDTO
 *
 * Objeto de Transferencia de Datos para la actualización de productos.
 * Este DTO se utiliza exclusivamente para la actualización de productos existentes y no incluye el ID,
 * ya que este se maneja a través de la URL en el controlador.
 *
 * @package App\Modules\Products\DTO
 */
class UpdateProductDTO
{
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
     * Constructor de UpdateProductDTO.
     *
     * @param string $name
     * @param string|null $description
     * @param float $price
     * @param int $stock
     * @param string $productCategoryId
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $name,
        ?string $description,
        float $price,
        int $stock,
        string $productCategoryId
    ) {
        $this->validateInputs($name, $price, $stock, $productCategoryId);
        
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->productCategoryId = $productCategoryId;
    }

    /**
     * Valida los datos de entrada.
     *
     * @param string $name
     * @param float $price
     * @param int $stock
     * @param string $productCategoryId
     * @throws \InvalidArgumentException
     */
    private function validateInputs(string $name, float $price, int $stock, string $productCategoryId): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("El nombre del producto no puede estar vacío.");
        }
        if ($price <= 0) {
            throw new \InvalidArgumentException("El precio del producto debe ser mayor que cero.");
        }
        if ($stock < 0) {
            throw new \InvalidArgumentException("El stock del producto no puede ser negativo.");
        }
        if (empty($productCategoryId)) {
            throw new \InvalidArgumentException("La categoría del producto no puede estar vacía.");
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'product_category_id' => $this->productCategoryId
        ];
    }
}