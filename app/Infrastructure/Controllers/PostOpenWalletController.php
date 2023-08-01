<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Http\Requests\OpenWalletRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class PostOpenWalletController extends BaseController
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(UserDataSource $userDataSource, WalletDataSource $walletDataSource)
    {
        $this->userDataSource = $userDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function __invoke(OpenWalletRequest $openWalletRequest): JsonResponse
    {
        $user = $this->userDataSource->findById($openWalletRequest->input('user_id'));
        if (is_null($user)) {
            return response()->json([
                'description' => 'A user with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $walletId = $this->walletDataSource->saveWalletInCache();
        if ($walletId) {
            return response()->json([
                'description' => 'successful operation',
                'wallet_id' => str($walletId)
            ], Response::HTTP_OK);
        }
        return response()->json([
            'description' => 'cache is full',
        ], Response::HTTP_NOT_FOUND);
    }
}
