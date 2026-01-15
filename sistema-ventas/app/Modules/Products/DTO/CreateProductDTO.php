<?php

namespace App\Modules\Products\DTO;

/**
 * Clase CreateProductDTO
 *
 * Objeto de Transferencia de Datos para la creación de productos.
 * Este DTO se utiliza exclusivamente para la creación de nuevos productos y no incluye el ID,
 * ya que este es generado por el sistema.
 *
 * @package App\Modules\Products\DTO
 */
class CreateProductDTO
{
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
     * Constructor de CreateProductDTO.
     *
     * @param string $code
     * @param string $name
     * @param string|null $description
     * @param float $price
     * @param int $stock
     * @param string $productCategoryId
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $code,
        string $name,
        ?string $description,
        float $price,
        int $stock,
        string $productCategoryId
    ) {
        $this->validateInputs($code, $name, $price, $stock, $productCategoryId);
        
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->productCategoryId = $productCategoryId;
    }

    /**
     * Valida los datos de entrada.
     *
     * @param string $code
     * @param string $name
     * @param float $price
     * @param int $stock
     * @param string $productCategoryId
     * @throws \InvalidArgumentException
     */
    private function validateInputs(string $code, string $name, float $price, int $stock, string $productCategoryId): void
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("El código del producto no puede estar vacío.");
        }
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'product_category_id' => $this->productCategoryId
        ];
    }
}