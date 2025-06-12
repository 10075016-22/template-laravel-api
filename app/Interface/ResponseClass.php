<?php

namespace App\Interface;

interface ResponseInterface
{
    /**
     * Devuelve una respuesta exitosa con datos.
     *
     * @param  array $data
     * @param  string $message
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(array $data, string $message = 'OK', int $status = 200): \Illuminate\Http\JsonResponse;

    /**
     * Devuelve una respuesta de error con mensaje y código.
     *
     * @param  string $message
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message, array $errors = [], int $status = 500): \Illuminate\Http\JsonResponse;

    /**
     * Devuelve una respuesta de recurso no encontrado.
     *
     * @param  string $message
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound(string $message, array $errors = [], int $status = 404): \Illuminate\Http\JsonResponse;

    /**
     * Devuelve una respuesta de validación con errores.
     *
     * @param  array $errors
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationError(array $errors, int $status = 422): \Illuminate\Http\JsonResponse;
}

class ResponseClass implements ResponseInterface
{
    /**
     * Estructura base de la respuesta
     */
    protected function baseResponse($data, string $message, int $status): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ], $status);
    }

    /**
     * Estructura base de error
     */
    protected function baseError(string $message, array $errors = [], int $status): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $errors,
            'timestamp' => now()->toISOString()
        ], $status);
    }

    public function success($data, string $message = 'OK', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return $this->baseResponse($data, $message, $status);
    }

    public function error(string $message, array $errors = [], int $status = 500): \Illuminate\Http\JsonResponse
    {
        return $this->baseError($message, $errors, $status);
    }

    public function notFound(string $message, array $errors = [], int $status = 404): \Illuminate\Http\JsonResponse
    {
        return $this->baseError($message, $errors, $status);
    }

    public function validationError(array $errors, int $status = 422): \Illuminate\Http\JsonResponse
    {
        return $this->baseError('Validación fallida', $errors, $status);
    }
}
