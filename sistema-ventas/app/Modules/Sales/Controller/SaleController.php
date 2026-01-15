<?php

namespace App\Modules\Sales\Controller;

use App\Modules\Sales\Service\SaleService;
use App\Modules\Sales\DTO\CreateSaleDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;



class SaleController
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * @OA\Post(
     *     path="/api/sales",
     *     summary="Create a new sale",
     *     description="Create a new sale with the provided details",
     *     operationId="createSale",
     *     tags={"Sales"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sale data",
     *         @OA\JsonContent(
     *             required={"sale_date", "sale_details"},
     *             @OA\Property(property="sale_date", type="string", format="date", example="2024-01-14"),
     *             @OA\Property(
     *                 property="sale_details",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"product_id", "quantity", "unit_price", "subtotal"},
     *                     @OA\Property(property="product_id", type="string", format="uuid", example="123e4567-e89b-12d3-a456-426614174000"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="unit_price", type="number", format="float", example=10.99),
     *                     @OA\Property(property="subtotal", type="number", format="float", example=21.98)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sale created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="sale_date", type="string", format="date"),
     *             @OA\Property(property="total_amount", type="number", format="float"),
     *             @OA\Property(property="sale_status_id", type="string", format="uuid"),
     *             @OA\Property(
     *                 property="sale_details",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="string", format="uuid"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="unit_price", type="number", format="float"),
     *                     @OA\Property(property="subtotal", type="number", format="float")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Domain error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function createSale(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'sale_date' => 'required|date',
                'sale_details' => 'required|array|min:1',
                'sale_details.*.product_id' => 'required|uuid',
                'sale_details.*.quantity' => 'required|integer|min:1',
                'sale_details.*.unit_price' => 'required|numeric|min:0.01',
                'sale_details.*.subtotal' => 'required|numeric|min:0.01',
            ]);

            $dto = new CreateSaleDTO(
                $validated['sale_date'],
                $validated['sale_details']
            );

            $result = $this->saleService->createSale($dto);

            return response()->json($result, 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);

        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 400);

        } catch (\Throwable $e) {
            Log::error('Create sale error', ['exception' => $e]);
            return response()->json(['error' => 'Error interno al crear la venta'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/sales/{saleId}",
     *     summary="Get a sale by ID",
     *     description="Retrieve a sale by its ID",
     *     operationId="getSaleById",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="saleId",
     *         in="path",
     *         required=true,
     *         description="ID of the sale to retrieve",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sale retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="sale_date", type="string", format="date"),
     *             @OA\Property(property="total_amount", type="number", format="float"),
     *             @OA\Property(property="sale_status_id", type="string", format="uuid"),
     *             @OA\Property(
     *                 property="sale_details",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="string", format="uuid"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="unit_price", type="number", format="float"),
     *                     @OA\Property(property="subtotal", type="number", format="float")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
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
    * @OA\Get(
    *     path="/api/sales",
    *     tags={"Sales"},
    *     summary="Obtener todas las ventas",
    *     description="Devuelve una lista de todas las ventas registradas",
    *     @OA\Response(
    *         response=200,
    *         description="Lista de ventas",
    *         @OA\JsonContent(
    *             type="array",
    *             @OA\Items(
    *                 type="object",
    *                 @OA\Property(property="id", type="string", format="uuid"),
    *                 @OA\Property(property="sale_date", type="string", format="date"),
    *                 @OA\Property(property="total_amount", type="number", format="float"),
    *                 @OA\Property(property="sale_status_id", type="string", format="uuid"),
    *                 @OA\Property(
    *                     property="sale_details",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="product_id", type="string", format="uuid"),
    *                         @OA\Property(property="quantity", type="integer"),
    *                         @OA\Property(property="unit_price", type="number", format="float"),
    *                         @OA\Property(property="subtotal", type="number", format="float")
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
       $sales = $this->saleService->getAllSales();
       return response()->json($sales);
   }


    /**
     * @OA\Put(
     *     path="/api/sales/{saleId}",
     *     summary="Update sale status",
     *     description="Update the status of a sale",
     *     operationId="updateSale",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="saleId",
     *         in="path",
     *         required=true,
     *         description="ID of the sale to update",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sale status data",
     *         @OA\JsonContent(
     *             required={"sale_status_code"},
     *             @OA\Property(property="sale_status_code", type="string", example="completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sale updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Domain error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function updateSale(Request $request, string $saleId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'sale_status_code' => 'required|string'
            ]);

            $updated = $this->saleService->updateSaleStatus(
                $saleId,
                $validated['sale_status_code']
            );

            if (!$updated) {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }

            return response()->json(['message' => 'Venta actualizada correctamente']);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);

        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 400);

        } catch (\Throwable $e) {
            Log::error('Update sale error', ['exception' => $e]);
            return response()->json(['error' => 'Error interno al actualizar la venta'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/sales/{saleId}",
     *     summary="Delete a sale",
     *     description="Delete a sale by its ID",
     *     operationId="deleteSale",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="saleId",
     *         in="path",
     *         required=true,
     *         description="ID of the sale to delete",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sale deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function deleteSale(string $saleId): JsonResponse
    {
        if (!$this->saleService->deleteSale($saleId)) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        return response()->json(['message' => 'Venta eliminada correctamente']);
    }
}
