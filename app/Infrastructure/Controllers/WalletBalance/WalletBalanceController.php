<?php

namespace App\Infrastructure\Controllers\WalletBalance;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\WalletBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class WalletBalanceController extends BaseController
{
    /**
     * @throws WalletNotFoundException
     */
    public function __invoke(
        $walletId,
        BalanceWalletIdValidator $walletIdValidator,
        WalletBalanceService $walletBalanceService
    ): JsonResponse {
        if (!$walletIdValidator->validateWalletId($walletId)) {
            return response()->json(
                [
                    'error' => 'Bad Request',
                    'message' => $walletIdValidator->getMessage($walletId)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $balance = $walletBalanceService->execute($walletId);

        return response()->json(
            [
                'balance_usd' => $balance
            ],
            Response::HTTP_OK
        );
    }
}
