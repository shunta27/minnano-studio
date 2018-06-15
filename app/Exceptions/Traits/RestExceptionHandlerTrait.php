<?php

namespace App\Exceptions\Traits;

use App\Exceptions\Traits\StatusCodeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use GuzzleHttp\Exception\ClientException;
use Exception;

trait RestExceptionHandlerTrait
{
    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $exception): JsonResponse
    {
        switch(true)
        {
            case $this->isAuthenticationException($exception);
                $ret = $this->apiUnauthenticated();
                break;
            
            case $this->isAuthorizationException($exception);
                $ret = $this->apiUnauthorization();
                break;

            case $this->isModelNotFoundException($exception):
                $ret = $this->modelNotFound();
                break;
            
            case $this->isValidationException($exception);
                $ret = $this->validationError($exception);
                break;
            
            case $this->isMethodNotAllowedHttpException($exception);
                $ret = $this->methodNotAllowedHttp();
                break;
            
            case $this->isGuzzleHttpClientException($exception);
                $ret = $this->apiUnauthenticated();
                break;
            
            default:
                $ret = $this->badRequest();
        }

        return $ret;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message = 'Bad request', $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::BAD_REQUEST()->valueOf();

        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message = 'Record not found', $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::RECORD_NOT_FOUND()->valueOf();

        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response for Unauthenticated exception.
     *
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiUnauthenticated($message = 'Unauthenticated', $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::UNAUTHENTICATED()->valueOf();

        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response for Unauthorization exception.
     *
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiUnAuthorization($message = 'Unauthorization', $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::FORBIDDEN()->valueOf();

        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response for Validation exception.
     *
     * @param Illuminate\Contracts\Validation\ValidationException $exception
     * @param Request $request
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationError(
        ValidationException $exception,
        $message = 'Validation error occurred',
        $statusCode = null
    ): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::VALIDATION_ERROR()->valueOf();

        return $this->jsonResponse([
            'error' => $message,
            'data' => $exception->errors(),
        ], $statusCode);
    }

    /**
     * Returns json response for Method not allowed exception.
     *
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function methodNotAllowedHttp($message = 'Method not allowed', $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::METHOD_NOT_ALLOWED()->valueOf();

        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? StatusCode::INTERNAL_SERVER_ERROR()->valueOf();

        $payload = $payload ?? [];
        
        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $exception
     * @return bool
     */
    protected function isModelNotFoundException(Exception $exception): bool
    {
        return $exception instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is unauthenticated.
     *
     * @param Exception $exception
     * @return bool
     */
    protected function isAuthenticationException(Exception $exception): bool
    {
        return $exception instanceof AuthenticationException;
    }

    /**
     * Determines if the given exception is unauthorization.
     *
     * @param Exception $exception
     * @return bool
     */
    protected function isAuthorizationException(Exception $exception): bool
    {
        return $exception instanceof AuthorizationException;
    }

    /**
     * Determines if the given exception is validation error.
     *
     * @param Exception $exception
     * @return bool
     */
    protected function isValidationException(Exception $exception): bool
    {
        return $exception instanceof ValidationException;
    }

    /**
     * Determines if the given exception is method not allowed.
     *
     * @param Exception $exception
     * @return bool
     */
    protected function isMethodNotAllowedHttpException(Exception $exception): bool
    {
        return $exception instanceof MethodNotAllowedHttpException;
    }

    /**
     * Undocumented function
     *
     * @param Exception $exception
     * @return bool
     */
    protected function isGuzzleHttpClientException(Exception $exception): bool
    {
        return $exception instanceof ClientException;
    }
}