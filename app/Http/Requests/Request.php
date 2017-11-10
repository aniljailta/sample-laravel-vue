<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest
{
    public $redirectOnFail = true;

    /*
     * Contains instance of custom validator
     */
    public $validator;

    /*
     * Contains rules for laravel's validator
     */
    protected $rules;

    /**
     * Execute validation (by prototype of laravel's validator and usage custom validator)
     */
    public function runValidator()
    {
        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        } elseif (! $this->validator->passes()) {
            if ($this->validator instanceof Validator) {
                $this->failedValidation($this->validator);
            } else {
                // this is for validator assistant..
                $this->failedValidation($this->validator->instance());
            }
        }
    }

    /**
     * Return 403 error, if request didn't passed authorize method
     * @return Response
     */
    public function forbiddenResponse()
    {
        if ($this->expectsJson()) {
            return new JsonResponse('Forbidden', 403);
        }

        return new Response(view('errors.403'), 403);
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        if ($this->redirectOnFail) {
            return $this->redirector->to($this->getRedirectUrl())
                ->withInput($this->except($this->dontFlash))
                ->withErrors($errors, $this->errorBag);
        }

        return $this->errorResponse($errors);
    }

    /**
     * @param $errors
     */
    public function errorResponse($errors) {}

    /**
     * @param $route
     */
    public function setRedirectRoute($route)
    {
        $this->redirectRoute = $route;
    }
    
    /**
     * @param $url
     */
    public function setRedirect($url)
    {
        $this->redirect = $url;
    }
}
