<?php

namespace App\Infrastructure\Controllers;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\WalletBalanceService;
use App\Validators\WalletIdValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetsWalletBalanceController extends BaseController
{
    /**
     * @throws WalletNotFoundException
     */
    public function __invoke(
        $wallet_id,
        WalletIdValidator $walletIdValidator,
        WalletBalanceService $walletBalanceService
    ): JsonResponse {
        if (!$walletIdValidator->validateWalletId($wallet_id)) {
            return response()->json(['error' => 'Bad Request',
                'message' => $walletIdValidator->getMessage($wallet_id)], Response::HTTP_BAD_REQUEST);
        }

        $balance = $walletBalanceService->getsBalance($wallet_id);

        return response()->json(['balance_usd' => $balance], Response::HTTP_OK);
    }
}
