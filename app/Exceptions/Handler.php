<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return response($request->method() . ' method is not supported for this route.', 405);
        });

        // $this->renderable(function (NotFoundHttpException $e, Request $request) {
        //     return response("NotFoundHttpException : ". $e->getMessage()." on file \"".$e->getFile()."\" line : ".$e->getLine(), 404);
        // });

        $this->renderable(function (QueryException $e, Request $request) {
            return response("QueryException : ".$e->getMessage(), 500);
        });

        $this->renderable(function (ModelNotFoundException $e) {
            return response($e->getMessage() . ' doesn\'t exist', 500);
        });

        // $this->renderable(function (Exception $e) {
        //     return response($e->getMessage()." on file \"".$e->getFile()."\" line : ".$e->getLine(), 500);
        // });

        $this->renderable(function (FileNotFoundException $e) {
            return response($e->getMessage(), 500);
        });
    }
}