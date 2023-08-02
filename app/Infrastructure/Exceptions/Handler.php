<?php

namespace App\Infrastructure\Exceptions;

use App\Application\Exceptions\UserNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
            //
        });
    }

    /**
     * @param Request $request
     * @param Throwable $genericException
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $genericException): Response
    {
        if ($genericException instanceof UserNotFoundException) {
            return response()->json([
                'description' => $genericException->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $genericException);
    }
}
