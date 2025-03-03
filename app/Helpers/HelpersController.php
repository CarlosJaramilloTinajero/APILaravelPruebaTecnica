<?php

namespace App\Helpers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Clase de ayuda para generar respuestas HTTP en la API.
 */
class HelpersController
{
    /**
     * Retorna una respuesta HTTP con un mensaje de error y un código de estado 500 (error interno del servidor).
     *
     * @param string $msg Mensaje de error opcional.
     * 
     * @return Response
     */
    public function responseFailFatalApi(string $msg = ''): Response
    {
        return response('Error interno del servidor: ' . $msg, 500);
    }

    /**
     * Retorna una respuesta HTTP con un estado de éxito falso ('status' => false) y un mensaje de error.
     *
     * @param string $msg Mensaje de error.
     * @param int $status Código de estado HTTP personalizado
     * 
     * @return JsonResponse
     */
    public function responseFailApi(string $msg = '', int $status = 200): JsonResponse
    {
        return response()->json(['status' => false, 'msg' => $msg], $status);
    }

    /**
     * Retorna una respuesta HTTP con un estado de éxito verdadero ('status' => true) y datos adicionales.
     *
     * @param array $data Datos de respuesta.
     * @param int $status Código de estado HTTP personalizado.
     * 
     * @return JsonResponse
     */
    public function responseSuccessApi(array $data = [], int $status = 200): JsonResponse
    {
        return response()->json(['status' => true, 'data' => $data], $status);
    }
}
