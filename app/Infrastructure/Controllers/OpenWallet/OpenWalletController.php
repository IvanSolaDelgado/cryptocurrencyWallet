<?php

namespace App\Infrastructure\Controllers\OpenWallet;

use App\Application\Services\OpenWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class OpenWalletController extends BaseController
{
    public function __invoke(OpenWalletRequest $openWalletRequest, OpenWalletService $openWalletService): JsonResponse
    {
        $userId = $openWalletRequest->input('user_id');
        $walletId = $openWalletService->createWallet($userId);

        if (str_contains($walletId, 'Cache is full')) {
            return response()->json([
                'description' => 'cache is full',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'description' => 'successful operation',
            'wallet_id' => str($walletId)
        ], Response::HTTP_OK);
    }
}
