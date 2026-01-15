<?php

namespace App\Modules\ProductCategories\DTO;

/**
 * Clase ProductCategoryResponseDTO
 *
 * Objeto de Transferencia de Datos para la respuesta de categorías de productos.
 * Este DTO se utiliza para devolver información de la categoría de producto, incluyendo el ID generado por el sistema.
 *
 * @package App\Modules\ProductCategories\DTO
 */
class ProductCategoryResponseDTO
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
     * Constructor de ProductCategoryResponseDTO.
     *
     * @param string $id
     * @param string $code
     * @param string $name
     * @param string|null $description
     */
    public function __construct(
        string $id,
        string $code,
        string $name,
        ?string $description
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
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
            'description' => $this->description
        ];
    }
}