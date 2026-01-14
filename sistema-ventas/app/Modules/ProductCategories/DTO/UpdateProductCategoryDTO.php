<?php

namespace App\Modules\ProductCategories\DTO;

/**
 * Clase UpdateProductCategoryDTO
 *
 * Objeto de Transferencia de Datos para la actualización de categorías de productos.
 * Este DTO se utiliza exclusivamente para la actualización de categorías de productos existentes y no incluye el ID,
 * ya que este se maneja a través de la URL en el controlador.
 *
 * @package App\Modules\ProductCategories\DTO
 */
class UpdateProductCategoryDTO
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
     * Constructor de UpdateProductCategoryDTO.
     *
     * @param string $name
     * @param string|null $description
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $name,
        ?string $description
    ) {
        $this->validateInputs($name);
        
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Valida los datos de entrada.
     *
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function validateInputs(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("El nombre de la categoría no puede estar vacío.");
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
            'description' => $this->description
        ];
    }
}