<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API de Sistema de Ventas",
 *     version="1.0.0",
 *     description="Documentación de la API para el sistema de ventas",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor de la API"
 * )
 *
 * @OA\PathItem(
 *     path="/api"
 * )
 */
class SwaggerController extends BaseController
{
    // Este controlador se utiliza únicamente para la generación de la documentación de Swagger.
}