<?php

use App\Infrastructure\Controllers\WalletBalanceController;
use App\Infrastructure\Controllers\WalletCryptocurrenciesController;
use App\Infrastructure\Controllers\OpenWallet\OpenWalletController;
use App\Infrastructure\Controllers\BuyCoinController;
use App\Infrastructure\Controllers\SellCoinController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/wallet/{wallet_id}', WalletCryptocurrenciesController::class);
Route::post('/wallet/open', OpenWalletController::class);
Route::post('/coin/buy', BuyCoinController::class);
Route::post('/coin/sell', SellCoinController::class);
Route::get('/wallet/{wallet_id}/balance', WalletBalanceController::class);
