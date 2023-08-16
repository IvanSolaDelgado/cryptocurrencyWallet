<?php

namespace App\Infrastructure\Controllers\OpenWallet;

use App\Application\Exceptions\CacheFullException;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\OpenWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class OpenWalletController extends BaseController
{
    /**
     * @throws CacheFullException
     * @throws UserNotFoundException
     */
    public function __invoke(OpenWalletRequest $openWalletRequest, OpenWalletService $openWalletService): JsonResponse
    {
        $userId = $openWalletRequest->input('user_id');
        $walletId = $openWalletService->execute($userId);

        return response()->json([
            'description' => 'successful operation',
            'wallet_id' => str($walletId)
        ], Response::HTTP_OK);
    }
}
