<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use App\Helpers\DefaultResponseHelper;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // start custom code
        if($exception instanceof \Illuminate\Auth\Access\AuthorizationException){
            $response = DefaultResponseHelper::error($exception->getMessage(), 403);
            return response()->json($response, 403);
        } else if($exception->getStatusCode() == 404){
            $response = DefaultResponseHelper::error('URI não encontrada', 404);
            return response()->json($response, 404);
        } else if($exception->getStatusCode() == 405){
            $response = DefaultResponseHelper::error('Método não permitido', 405);
            return response()->json($response, 405);
        } else if($exception->getStatusCode() == 500){
            $response = DefaultResponseHelper::error('Ocorreu um erro interno do servidor', 500);
            return response()->json($response, 500);
        }
        // end custom code
        return parent::render($request, $exception);
    }
}
