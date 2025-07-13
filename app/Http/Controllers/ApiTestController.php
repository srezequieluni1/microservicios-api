<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para demostrar funcionalidad del cliente API
 * Proporciona endpoints de ejemplo para probar el cliente API
 */
class ApiTestController extends Controller
{
    /**
     * Endpoint GET de ejemplo
     */
    public function getExample(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'GET request exitoso',
            'timestamp' => now(),
            'method' => 'GET',
            'data' => [
                'ejemplo' => 'Datos de ejemplo',
                'usuario' => 'Anónimo',
                'parametros' => $request->query()
            ]
        ], 200);
    }

    /**
     * Endpoint POST de ejemplo
     */
    public function postExample(Request $request): JsonResponse
    {
        $data = $request->all();

        return response()->json([
            'message' => 'POST request exitoso',
            'timestamp' => now(),
            'method' => 'POST',
            'received_data' => $data,
            'data_count' => count($data),
            'content_type' => $request->header('Content-Type')
        ], 201);
    }

    /**
     * Endpoint PUT de ejemplo
     */
    public function putExample(Request $request, $id = null): JsonResponse
    {
        $data = $request->all();

        return response()->json([
            'message' => 'PUT request exitoso',
            'timestamp' => now(),
            'method' => 'PUT',
            'id' => $id ?? 'default',
            'updated_data' => $data,
            'status' => 'actualizado'
        ], 200);
    }

    /**
     * Endpoint DELETE de ejemplo
     */
    public function deleteExample(Request $request, $id = null): JsonResponse
    {
        return response()->json([
            'message' => 'DELETE request exitoso',
            'timestamp' => now(),
            'method' => 'DELETE',
            'deleted_id' => $id ?? 'default',
            'status' => 'eliminado'
        ], 200);
    }

    /**
     * Endpoint PATCH de ejemplo
     */
    public function patchExample(Request $request, $id = null): JsonResponse
    {
        $data = $request->all();

        return response()->json([
            'message' => 'PATCH request exitoso',
            'timestamp' => now(),
            'method' => 'PATCH',
            'id' => $id ?? 'default',
            'patched_fields' => array_keys($data),
            'patched_data' => $data
        ], 200);
    }

    /**
     * Endpoint que simula diferentes códigos de estado HTTP
     */
    public function statusExample(Request $request): JsonResponse
    {
        $status = $request->query('status', 200);
        $status = (int) $status;

        // Validar que el status sea válido
        if ($status < 100 || $status > 599) {
            $status = 400;
        }

        $messages = [
            200 => 'OK - Solicitud exitosa',
            201 => 'Created - Recurso creado exitosamente',
            400 => 'Bad Request - Solicitud inválida',
            401 => 'Unauthorized - No autorizado',
            403 => 'Forbidden - Prohibido',
            404 => 'Not Found - Recurso no encontrado',
            500 => 'Internal Server Error - Error interno del servidor'
        ];

        $message = $messages[$status] ?? "Status Code $status";

        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'timestamp' => now(),
            'demo' => true
        ], $status);
    }

    /**
     * Endpoint que simula demora en la respuesta
     */
    public function delayExample(Request $request): JsonResponse
    {
        $delay = $request->query('delay', 1);
        $delay = min(10, max(0, (int) $delay)); // Máximo 10 segundos

        sleep($delay);

        return response()->json([
            'message' => "Respuesta después de {$delay} segundos",
            'delay_seconds' => $delay,
            'timestamp' => now(),
            'performance_test' => true
        ], 200);
    }

    /**
     * Endpoint que devuelve datos de usuario simulados
     */
    public function usersExample(Request $request): JsonResponse
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Juan Pérez',
                'email' => 'juan@ejemplo.com',
                'role' => 'admin',
                'created_at' => '2025-01-01T00:00:00Z'
            ],
            [
                'id' => 2,
                'name' => 'María García',
                'email' => 'maria@ejemplo.com',
                'role' => 'user',
                'created_at' => '2025-01-02T00:00:00Z'
            ],
            [
                'id' => 3,
                'name' => 'Carlos López',
                'email' => 'carlos@ejemplo.com',
                'role' => 'moderator',
                'created_at' => '2025-01-03T00:00:00Z'
            ]
        ];

        return response()->json([
            'data' => $users,
            'total' => count($users),
            'timestamp' => now(),
            'endpoint' => 'users'
        ], 200);
    }

    /**
     * Endpoint que requiere headers específicos
     */
    public function headersExample(Request $request): JsonResponse
    {
        $requiredHeader = $request->header('X-API-Key');

        if (!$requiredHeader) {
            return response()->json([
                'error' => 'Missing required header: X-API-Key',
                'required_headers' => ['X-API-Key'],
                'example' => 'X-API-Key: tu-api-key-aqui'
            ], 400);
        }

        return response()->json([
            'message' => 'Headers válidos',
            'api_key' => $requiredHeader,
            'all_headers' => $request->headers->all(),
            'timestamp' => now()
        ], 200);
    }
}
