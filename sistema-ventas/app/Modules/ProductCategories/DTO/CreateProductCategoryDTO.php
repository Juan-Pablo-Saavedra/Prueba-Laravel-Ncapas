<?php

namespace App\Modules\ProductCategories\DTO;

/**
 * Clase CreateProductCategoryDTO
 *
 * Objeto de Transferencia de Datos para la creación de categorías de productos.
 * Este DTO se utiliza exclusivamente para la creación de nuevas categorías de productos y no incluye el ID,
 * ya que este es generado por el sistema.
 *
 * @package App\Modules\ProductCategories\DTO
 */
class CreateProductCategoryDTO
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
     * Constructor de CreateProductCategoryDTO.
     *
     * @param string $code
     * @param string $name
     * @param string|null $description
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $code,
        string $name,
        ?string $description
    ) {
        $this->validateInputs($code, $name);
        
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Valida los datos de entrada.
     *
     * @param string $code
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function validateInputs(string $code, string $name): void
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("El código de la categoría no puede estar vacío.");
        }
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}