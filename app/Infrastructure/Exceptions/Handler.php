<?php

namespace App\Infrastructure\Exceptions;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
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
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception): Response
    {
        if (
            $exception instanceof UserNotFoundException ||
            $exception instanceof WalletNotFoundException ||
            $exception instanceof CoinNotFoundException
        ) {
            return response()->json([
                'description' => $exception->getMessage()
            ], $exception->getCode());
        }

        return parent::render($request, $exception);
    }
}
