<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FlattenException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \App\Exceptions\ValidationException::class,
        \App\Exceptions\BusinessException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $e = $this->prepareException($exception);

        if ($request->ajax() || $request->wantsJson()) {

            if ($e instanceof AuthenticationException) {
                $response = $this->unauthenticated($request, $e);
            } elseif ($e instanceof ValidationException) {
                $response = $this->convertValidationExceptionToResponse($e, $request);
            } else {
                $e = FlattenException::create($e);
                if (config('app.debug')) {
                    return new JsonResponse("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}", $e->getStatusCode(), $e->getHeaders());
                }

                $message = 'Oops, something went wrong';
                if (!empty(Response::$statusTexts[$e->getStatusCode()])) {
                    $message = Response::$statusTexts[$e->getStatusCode()];
                }

                return new JsonResponse($message, $e->getStatusCode(), $e->getHeaders());
            }

            return $response;
        }

        if ($e instanceof \App\Exceptions\ValidationException) {
            return $this->convertCustomValidationExceptionToResponse($e, $request);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    /**
     * @param \App\Exceptions\ValidationException $e
     * @param                                     $request
     * @return $this|JsonResponse|null|\Symfony\Component\HttpFoundation\Response
     */
    protected function convertCustomValidationExceptionToResponse(\App\Exceptions\ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        $errors = $e->getMessages();

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()->withInput($request->input())->withErrors($errors);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json($exception->errors(), $exception->status);
    }
}
