<?php

namespace App\Modules\Sales\Controller;

use App\Modules\Sales\Service\SaleService;
use App\Modules\Sales\DTO\CreateSaleDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la gestión de ventas.
 *
 * Este controlador maneja las operaciones CRUD para las ventas,
 * incluyendo la creación, lectura, actualización y eliminación de ventas.
 */

/**
 * Clase SaleController
 *
 * Controlador para gestionar las solicitudes relacionadas con ventas.
 * Implementa el patrón de controlador para manejar las peticiones HTTP.
 *
 * @package App\Modules\Sales\Controller
 */
class SaleController
{
    /**
     * @var SaleService
     */
    private $saleService;

    /**
     * Constructor de SaleController.
     *
     * @param SaleService $saleService
     */
    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Crea una nueva venta.
     *
     * Este endpoint permite la creación de una nueva venta en el sistema.
     * Se validan los datos de entrada y se devuelve una respuesta con la venta creada.
     *
     * @OA\Post(
     *     path="/api/sales",
     *     tags={"Sales"},
     *     summary="Crear una nueva venta",
     *     description="Crea una nueva venta en el sistema",
     *     operationId="createSale",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la venta a crear",
     *         @OA\JsonContent(
     *             required={"sale_date", "sale_details"},
     *             @OA\Property(property="sale_date", type="string", format="date", example="2024-01-14"),
     *             @OA\Property(property="sale_details", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="unit_price", type="number", format="float", example=100.50),
     *                     @OA\Property(property="subtotal", type="number", format="float", example=201.00)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Venta creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="sale", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440006"),
     *                 @OA\Property(property="sale_date", type="string", format="date", example="2024-01-14"),
     *                 @OA\Property(property="sale_details", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="unit_price", type="number", format="float", example=100.50),
     *                         @OA\Property(property="subtotal", type="number", format="float", example=201.00)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Venta creada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo sale_date es obligatorio.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al crear la venta")
     *         )
     *     )
     * )
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la venta.
     * @return JsonResponse La respuesta JSON con la venta creada o un mensaje de error.
     */
    public function createSale(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'sale_date' => 'required|date',
                'sale_details' => 'required|array|min:1',
                'sale_details.*.product_id' => 'required|string',
                'sale_details.*.quantity' => 'required|integer|min:1',
                'sale_details.*.unit_price' => 'required|numeric|min:0.01',
                'sale_details.*.subtotal' => 'required|numeric|min:0.01',
            ]);

            $createSaleDTO = new CreateSaleDTO(
                $validatedData['sale_date'],
                $validatedData['sale_details']
            );

            $result = $this->saleService->createSale($createSaleDTO);

            return response()->json($result, 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear la venta: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene una venta por su ID.
     *
     * Este endpoint devuelve la información de una venta específica según su ID.
     * Si la venta no existe, se devuelve un mensaje de error.
     *
     * @OA\Get(
     *     path="/api/sales/{saleId}",
     *     tags={"Sales"},
     *     summary="Obtener una venta por ID",
     *     description="Devuelve la información de una venta específica",
     *     operationId="getSaleById",
     *     @OA\Parameter(
     *         name="saleId",
     *         in="path",
     *         description="ID de la venta",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la venta",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *             @OA\Property(property="sale_date", type="string", format="date", example="2024-01-14"),
     *             @OA\Property(property="sale_details", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="unit_price", type="number", format="float", example=100.50),
     *                     @OA\Property(property="subtotal", type="number", format="float", example=201.00)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Venta no encontrada")
     *         )
     *     )
     * )
     *
     * @param string $saleId El ID de la venta a buscar.
     * @return JsonResponse La respuesta JSON con la información de la venta o un mensaje de error.
     */
    public function getSaleById(string $saleId): JsonResponse
    {
        $sale = $this->saleService->getSaleById($saleId);

        if (!$sale) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        return response()->json($sale);
    }

    /**
     * Obtiene todas las ventas.
     *
     * Este endpoint devuelve una lista de todas las ventas registradas en el sistema.
     *
     * @OA\Get(
     *     path="/api/sales",
     *     tags={"Sales"},
     *     summary="Obtener todas las ventas",
     *     description="Devuelve una lista de todas las ventas registradas en el sistema",
     *     operationId="getAllSales",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de ventas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *                 @OA\Property(property="sale_date", type="string", format="date", example="2024-01-14"),
     *                 @OA\Property(property="sale_details", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="unit_price", type="number", format="float", example=100.50),
     *                         @OA\Property(property="subtotal", type="number", format="float", example=201.00)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @return JsonResponse La respuesta JSON con la lista de ventas.
     */
    public function getAllSales(): JsonResponse
    {
        return response()->json(
            $this->saleService->getAllSales()
        );
    }

    /**
     * Actualiza el estado de una venta.
     *
     * Este endpoint permite la actualización del estado de una venta existente en el sistema.
     * Se validan los datos de entrada y se devuelve una respuesta con el resultado de la operación.
     *
     * @OA\Put(
     *     path="/api/sales/{saleId}",
     *     tags={"Sales"},
     *     summary="Actualizar estado de una venta",
     *     description="Actualiza el estado de una venta existente en el sistema",
     *     operationId="updateSale",
     *     @OA\Parameter(
     *         name="saleId",
     *         in="path",
     *         description="ID de la venta",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     * @OA\RequestBody(
     *         required=true,
     *         description="Datos del estado de la venta a actualizar",
     *         @OA\JsonContent(
     *             required={"sale_status_id"},
     *             @OA\Property(property="sale_status_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Venta actualizada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo sale_status_id es obligatorio.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Venta no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar la venta")
     *         )
     *     )
     * )
     *
     * @param Request $request La solicitud HTTP que contiene los datos actualizados de la venta.
     * @param string $saleId El ID de la venta a actualizar.
     * @return JsonResponse La respuesta JSON con el resultado de la operación o un mensaje de error.
     */
    public function updateSale(Request $request, string $saleId): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'sale_status_id' => 'required|string|exists:sale_statuses,id',
            ]);

            $updated = $this->saleService->updateSale($saleId, $validatedData);

            if (!$updated) {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }

            return response()->json(['message' => 'Venta actualizada exitosamente']);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la venta: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar la venta'], 500);
        }
    }

    /**
     * Elimina una venta.
     *
     * Este endpoint permite la eliminación de una venta del sistema.
     * Si la venta no existe, se devuelve un mensaje de error.
     *
     * @OA\Delete(
     *     path="/api/sales/{saleId}",
     *     tags={"Sales"},
     *     summary="Eliminar una venta",
     *     description="Elimina una venta del sistema",
     *     operationId="deleteSale",
     *     @OA\Parameter(
     *         name="saleId",
     *         in="path",
     *         description="ID de la venta",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Venta eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Venta no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar la venta")
     *         )
     *     )
     * )
     *
     * @param string $saleId El ID de la venta a eliminar.
     * @return JsonResponse La respuesta JSON con el resultado de la operación o un mensaje de error.
     */
    public function deleteSale(string $saleId): JsonResponse
    {
        $deleted = $this->saleService->deleteSale($saleId);

        if (!$deleted) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        return response()->json(['message' => 'Venta eliminada exitosamente']);
    }
}
