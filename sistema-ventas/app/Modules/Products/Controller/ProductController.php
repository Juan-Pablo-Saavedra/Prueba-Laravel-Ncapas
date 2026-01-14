<?php

namespace App\Modules\Products\Controller;

use App\Modules\Products\Service\ProductService;
use App\Modules\Products\DTO\CreateProductDTO;
use App\Modules\Products\DTO\UpdateProductDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la gestión de productos.
 *
 * Este controlador maneja las operaciones CRUD para los productos,
 * incluyendo la creación, lectura, actualización y eliminación de productos.
 */

/**
 * Clase ProductController
 *
 * Controlador para gestionar las solicitudes relacionadas con productos.
 * Implementa el patrón de controlador para manejar las peticiones HTTP.
 *
 * @package App\Modules\Products\Controller
 */
class ProductController
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * Constructor de ProductController.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Crea un nuevo producto.
     *
     * Este endpoint permite la creación de un nuevo producto en el sistema.
     * Se validan los datos de entrada y se devuelve una respuesta con el producto creado.
     *
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Crear un nuevo producto",
     *     description="Crea un nuevo producto en el sistema",
     *     operationId="createProduct",
     * @OA\RequestBody(
      *         required=true,
      *         description="Datos del producto a crear",
      *         @OA\JsonContent(
      *             required={"code", "name", "price", "stock", "product_category_id"},
      *                 @OA\Property(property="code", type="string", example="PROD001"),
      *                 @OA\Property(property="name", type="string", example="Producto de ejemplo"),
      *                 @OA\Property(property="description", type="string", example="Descripción del producto"),
      *                 @OA\Property(property="price", type="number", format="float", example=10.99),
      *                 @OA\Property(property="stock", type="integer", example=100),
      *             @OA\Property(property="product_category_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440001")
      *         )
      *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="product", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440006"),
     *             @OA\Property(property="code", type="string", example="PROD001"),
     *             @OA\Property(property="name", type="string", example="Producto de ejemplo"),
     *             @OA\Property(property="description", type="string", example="Descripción del producto"),
     *             @OA\Property(property="price", type="number", format="float", example=10.99),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="product_category_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440001")
     *             ),
     *             @OA\Property(property="message", type="string", example="Producto creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El código del producto no puede estar vacío.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al crear el producto")
     *         )
     *     )
     * )
     *
     * @param Request $request La solicitud HTTP que contiene los datos del producto.
     * @return JsonResponse La respuesta JSON con el producto creado o un mensaje de error.
     */
    public function createProduct(Request $request): JsonResponse
    {
        try {
            // Validate required inputs
            $validatedData = $request->validate([
                'code' => 'required|string',
                'name' => 'required|string',
                'price' => 'required|numeric|min:0.01',
                'stock' => 'required|integer|min:0',
                'product_category_id' => 'required|string|exists:product_categories,id',
                'description' => 'nullable|string'
            ]);

            $createProductDTO = new CreateProductDTO(
                $validatedData['code'],
                $validatedData['name'],
                $validatedData['description'],
                (float) $validatedData['price'],
                (int) $validatedData['stock'],
                $validatedData['product_category_id']
            );

            $result = $this->productService->createProduct($createProductDTO);
            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear el producto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear el producto'], 500);
        }
    }

    /**
     * Obtiene un producto por su ID.
     *
     * Este endpoint devuelve la información de un producto específico según su ID.
     * Si el producto no existe, se devuelve un mensaje de error.
     *
     * @OA\Get(
     *     path="/api/products/{productId}",
     *     tags={"Products"},
     *     summary="Obtener un producto por ID",
     *     description="Devuelve la información de un producto específico",
     *     operationId="getProductById",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del producto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *             @OA\Property(property="code", type="string", example="PROD001"),
     *             @OA\Property(property="name", type="string", example="Producto de ejemplo"),
     *             @OA\Property(property="description", type="string", example="Descripción del producto"),
     *             @OA\Property(property="price", type="number", format="float", example=10.99),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="product_category_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Producto no encontrado")
     *         )
     *     )
     * )
     *
     * @param string $productId El ID del producto a buscar.
     * @return JsonResponse La respuesta JSON con la información del producto o un mensaje de error.
     */
    public function getProductById(string $productId): JsonResponse
    {
        $product = $this->productService->getProductById($productId);
        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        return response()->json($product);
    }

    /**
     * Obtiene todos los productos.
     *
     * Este endpoint devuelve una lista de todos los productos registrados en el sistema.
     *
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Obtener todos los productos",
     *     description="Devuelve una lista de todos los productos registrados en el sistema",
     *     operationId="getAllProducts",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *                 @OA\Property(property="code", type="string", example="PROD001"),
     *                 @OA\Property(property="name", type="string", example="Producto de ejemplo"),
     *                 @OA\Property(property="description", type="string", example="Descripción del producto"),
     *                 @OA\Property(property="price", type="number", format="float", example=10.99),
     *                 @OA\Property(property="stock", type="integer", example=100),
     *                 @OA\Property(property="product_category_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *             )
     *         )
     *     )
     * )
     *
     * @return JsonResponse La respuesta JSON con la lista de productos.
     */
    public function getAllProducts(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    /**
     * Actualiza un producto existente.
     *
     * Este endpoint permite la actualización de un producto existente en el sistema.
     * Se validan los datos de entrada y se devuelve una respuesta con el resultado de la operación.
     *
     * @OA\Put(
     *     path="/api/products/{productId}",
     *     tags={"Products"},
     *     summary="Actualizar un producto",
     *     description="Actualiza un producto existente en el sistema",
     *     operationId="updateProduct",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     * @OA\RequestBody(
      *         required=true,
      *         description="Datos del producto a actualizar",
      *         @OA\JsonContent(
      *             required={"name", "price", "stock", "product_category_id"},
      *             @OA\Property(property="name", type="string", example="Updated Smartphone"),
      *             @OA\Property(property="description", type="string", example="Updated smartphone description"),
      *             @OA\Property(property="price", type="number", format="float", example=649.99),
      *             @OA\Property(property="stock", type="integer", example=20),
      *             @OA\Property(property="product_category_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440001")
      *         )
      *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto actualizado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El nombre del producto no puede estar vacío.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Producto no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar el producto")
     *         )
     *     )
     * )
     *
     * @param Request $request La solicitud HTTP que contiene los datos actualizados del producto.
     * @param string $productId El ID del producto a actualizar.
     * @return JsonResponse La respuesta JSON con el resultado de la operación o un mensaje de error.
     */
    public function updateProduct(Request $request, string $productId): JsonResponse
    {
        try {
            // Validate required inputs
            $validatedData = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric|min:0.01',
                'stock' => 'required|integer|min:0',
                'product_category_id' => 'required|string|exists:product_categories,id',
                'description' => 'nullable|string'
            ]);

            $updateProductDTO = new UpdateProductDTO(
                $validatedData['name'],
                $validatedData['description'],
                (float) $validatedData['price'],
                (int) $validatedData['stock'],
                $validatedData['product_category_id']
            );

            $result = $this->productService->updateProduct($productId, $updateProductDTO);
            if (!$result) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            return response()->json(['message' => 'Producto actualizado exitosamente']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el producto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el producto'], 500);
        }
    }

    /**
     * Elimina un producto.
     *
     * Este endpoint permite la eliminación de un producto del sistema.
     * Si el producto no existe, se devuelve un mensaje de error.
     *
     * @OA\Delete(
     *     path="/api/products/{productId}",
     *     tags={"Products"},
     *     summary="Eliminar un producto",
     *     description="Elimina un producto del sistema",
     *     operationId="deleteProduct",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Producto no encontrado")
     *         )
     *     )
     * )
     *
     * @param string $productId El ID del producto a eliminar.
     * @return JsonResponse La respuesta JSON con el resultado de la operación o un mensaje de error.
     */
    public function deleteProduct(string $productId): JsonResponse
    {
        $result = $this->productService->deleteProduct($productId);
        if (!$result) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        return response()->json(['message' => 'Producto eliminado exitosamente']);
    }

    /**
     * Obtiene todos los productos de una categoría.
     *
     * Este endpoint devuelve una lista de productos que pertenecen a una categoría específica.
     *
     * @OA\Get(
     *     path="/api/products/category/{productCategoryId}",
     *     tags={"Products"},
     *     summary="Obtener productos por categoría",
     *     description="Devuelve una lista de productos que pertenecen a una categoría específica",
     *     operationId="getProductsByCategory",
     *     @OA\Parameter(
     *         name="productCategoryId",
     *         in="path",
     *         description="ID de la categoría de productos",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos de la categoría",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *                 @OA\Property(property="code", type="string", example="PROD001"),
     *                 @OA\Property(property="name", type="string", example="Producto de ejemplo"),
     *                 @OA\Property(property="description", type="string", example="Descripción del producto"),
     *                 @OA\Property(property="price", type="number", format="float", example=10.99),
     *                 @OA\Property(property="stock", type="integer", example=100),
     *                 @OA\Property(property="product_category_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *             )
     *         )
     *     )
     * )
     *
     * @param string $productCategoryId El ID de la categoría de productos.
     * @return JsonResponse La respuesta JSON con la lista de productos de la categoría.
     */
    public function getProductsByCategory(string $productCategoryId): JsonResponse
    {
        $products = $this->productService->getProductsByCategory($productCategoryId);
        return response()->json($products);
    }
}