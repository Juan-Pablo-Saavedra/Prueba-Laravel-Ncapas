<?php

namespace App\Modules\ProductCategories\Controller;

use App\Modules\ProductCategories\Service\ProductCategoryService;
use App\Modules\ProductCategories\DTO\CreateProductCategoryDTO;
use App\Modules\ProductCategories\DTO\UpdateProductCategoryDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la gestión de categorías de productos.
 *
 * Este controlador maneja las operaciones CRUD para las categorías de productos,
 * incluyendo la creación, lectura, actualización y eliminación de categorías.
 */

/**
 * Clase ProductCategoryController
 *
 * Controlador para gestionar las solicitudes relacionadas con categorías de productos.
 * Implementa el patrón de controlador para manejar las peticiones HTTP.
 *
 * @package App\Modules\ProductCategories\Controller
 */
class ProductCategoryController
{
    /**
     * @var ProductCategoryService
     */
    private $productCategoryService;

    /**
     * Constructor de ProductCategoryController.
     *
     * @param ProductCategoryService $productCategoryService
     */
    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Crea una nueva categoría de producto.
     *
     * Este endpoint permite la creación de una nueva categoría de producto en el sistema.
     * Se validan los datos de entrada y se devuelve una respuesta con la categoría creada.
     *
     * @OA\Post(
     *     path="/api/product-categories",
     *     tags={"ProductCategories"},
     *     summary="Crear una nueva categoría de producto",
     *     description="Crea una nueva categoría de producto en el sistema",
     *     operationId="createProductCategory",
     * @OA\RequestBody(
     *         required=true,
     *         description="Datos de la categoría de producto a crear",
     *         @OA\JsonContent(
     *             required={"code", "name"},
     *                 @OA\Property(property="code", type="string", example="CAT001"),
     *                 @OA\Property(property="name", type="string", example="Electrónicos"),
     *                 @OA\Property(property="description", type="string", example="Descripción de la categoría")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría de producto creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="product_category", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440006"),
     *             @OA\Property(property="code", type="string", example="CAT001"),
     *             @OA\Property(property="name", type="string", example="Electrónicos"),
     *             @OA\Property(property="description", type="string", example="Descripción de la categoría")
     *             ),
     *             @OA\Property(property="message", type="string", example="Categoría de producto creada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El código de la categoría no puede estar vacío.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al crear la categoría de producto")
     *         )
     *     )
     * )
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la categoría de producto.
     * @return JsonResponse La respuesta JSON con la categoría creada o un mensaje de error.
     */
    public function createProductCategory(Request $request): JsonResponse
    {
        try {
            // Validate required inputs
            $validatedData = $request->validate([
                'code' => 'required|string|unique:product_categories,code',
                'name' => 'required|string',
                'description' => 'nullable|string'
            ]);

            $createProductCategoryDTO = new CreateProductCategoryDTO(
                $validatedData['code'],
                $validatedData['name'],
                $validatedData['description']
            );

            $result = $this->productCategoryService->createProductCategory($createProductCategoryDTO);
            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear la categoría de producto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear la categoría de producto'], 500);
        }
    }

    /**
     * Obtiene una categoría de producto por su ID.
     *
     * Este endpoint devuelve la información de una categoría de producto específica según su ID.
     * Si la categoría no existe, se devuelve un mensaje de error.
     *
     * @OA\Get(
     *     path="/api/product-categories/{productCategoryId}",
     *     tags={"ProductCategories"},
     *     summary="Obtener una categoría de producto por ID",
     *     description="Devuelve la información de una categoría de producto específica",
     *     operationId="getProductCategoryById",
     *     @OA\Parameter(
     *         name="productCategoryId",
     *         in="path",
     *         description="ID de la categoría de producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la categoría de producto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *             @OA\Property(property="code", type="string", example="CAT001"),
     *             @OA\Property(property="name", type="string", example="Electrónicos"),
     *             @OA\Property(property="description", type="string", example="Descripción de la categoría")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría de producto no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Categoría de producto no encontrada")
     *         )
     *     )
     * )
     *
     * @param string $productCategoryId El ID de la categoría de producto a buscar.
     * @return JsonResponse La respuesta JSON con la información de la categoría de producto o un mensaje de error.
     */
    public function getProductCategoryById(string $productCategoryId): JsonResponse
    {
        $productCategory = $this->productCategoryService->getProductCategoryById($productCategoryId);
        if (!$productCategory) {
            return response()->json(['error' => 'Categoría de producto no encontrada'], 404);
        }
        return response()->json($productCategory);
    }

    /**
     * Obtiene todas las categorías de productos.
     *
     * Este endpoint devuelve una lista de todas las categorías de productos registradas en el sistema.
     *
     * @OA\Get(
     *     path="/api/product-categories",
     *     tags={"ProductCategories"},
     *     summary="Obtener todas las categorías de productos",
     *     description="Devuelve una lista de todas las categorías de productos registradas en el sistema",
     *     operationId="getAllProductCategories",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías de productos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *                 @OA\Property(property="code", type="string", example="CAT001"),
     *                 @OA\Property(property="name", type="string", example="Electrónicos"),
     *                 @OA\Property(property="description", type="string", example="Descripción de la categoría")
     *             )
     *         )
     *     )
     * )
     *
     * @return JsonResponse La respuesta JSON con la lista de categorías de productos.
     */
    public function getAllProductCategories(): JsonResponse
    {
        $productCategories = $this->productCategoryService->getAllProductCategories();
        return response()->json($productCategories);
    }

    /**
     * Actualiza una categoría de producto existente.
     *
     * Este endpoint permite la actualización de una categoría de producto existente en el sistema.
     * Se validan los datos de entrada y se devuelve una respuesta con el resultado de la operación.
     *
     * @OA\Put(
     *     path="/api/product-categories/{productCategoryId}",
     *     tags={"ProductCategories"},
     *     summary="Actualizar una categoría de producto",
     *     description="Actualiza una categoría de producto existente en el sistema",
     *     operationId="updateProductCategory",
     *     @OA\Parameter(
     *         name="productCategoryId",
     *         in="path",
     *         description="ID de la categoría de producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     * @OA\RequestBody(
     *         required=true,
     *         description="Datos de la categoría de producto a actualizar",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Electronics"),
     *             @OA\Property(property="description", type="string", example="Updated category description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría de producto actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría de producto actualizada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El nombre de la categoría no puede estar vacío.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría de producto no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Categoría de producto no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar la categoría de producto")
     *         )
     *     )
     * )
     *
     * @param Request $request La solicitud HTTP que contiene los datos actualizados de la categoría de producto.
     * @param string $productCategoryId El ID de la categoría de producto a actualizar.
     * @return JsonResponse La respuesta JSON con el resultado de la operación o un mensaje de error.
     */
    public function updateProductCategory(Request $request, string $productCategoryId): JsonResponse
    {
        try {
            // Validate required inputs
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string'
            ]);

            $updateProductCategoryDTO = new UpdateProductCategoryDTO(
                $validatedData['name'],
                $validatedData['description']
            );

            $result = $this->productCategoryService->updateProductCategory($productCategoryId, $updateProductCategoryDTO);
            if (!$result) {
                return response()->json(['error' => 'Categoría de producto no encontrada'], 404);
            }
            return response()->json(['message' => 'Categoría de producto actualizada exitosamente']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la categoría de producto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar la categoría de producto'], 500);
        }
    }

    /**
     * Elimina una categoría de producto.
     *
     * Este endpoint permite la eliminación de una categoría de producto del sistema.
     * Si la categoría no existe o está asociada a productos, se devuelve un mensaje de error.
     *
     * @OA\Delete(
     *     path="/api/product-categories/{productCategoryId}",
     *     tags={"ProductCategories"},
     *     summary="Eliminar una categoría de producto",
     *     description="Elimina una categoría de producto del sistema",
     *     operationId="deleteProductCategory",
     *     @OA\Parameter(
     *         name="productCategoryId",
     *         in="path",
     *         description="ID de la categoría de producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría de producto eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría de producto eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría de producto no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Categoría de producto no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflicto al eliminar la categoría",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No se puede eliminar la categoría porque está asociada a productos")
     *         )
     *     )
     * )
     *
     * @param string $productCategoryId El ID de la categoría de producto a eliminar.
     * @return JsonResponse La respuesta JSON con el resultado de la operación o un mensaje de error.
     */
    public function deleteProductCategory(string $productCategoryId): JsonResponse
    {
        try {
            $result = $this->productCategoryService->deleteProductCategory($productCategoryId);
            if ($result === 'conflict') {
                return response()->json(['error' => 'No se puede eliminar la categoría porque está asociada a productos'], 409);
            }
            if (!$result) {
                return response()->json(['error' => 'Categoría de producto no encontrada'], 404);
            }
            return response()->json(['message' => 'Categoría de producto eliminada exitosamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la categoría de producto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar la categoría de producto'], 500);
        }
    }
}