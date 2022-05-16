<?php

use Illuminate\Http\Request;
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

Route::middleware(['api'])->group(static function () {
//    Route::namespace('Api')->group(static function () {
//        Route::prefix('candles')->name('candles/')->group(static function () {
//            Route::get('/getCandles', 'CandlesController@index')->name('candles.index');
//            Route::get('/getCirCleHistory', 'CandlesController@getCirCleHistory')->name('candles.getCirCleHistory');
//        });
//        Route::prefix('walletGames')->name('walletGames/')->group(static function () {
//            Route::get('/getAmountWalletGame', 'WalletGamesController@getAmountWalletGame')->name('walletGames.getAmountWalletGame');
//        });
//
//        Route::prefix('orders')->name('orders/')->group(static function () {
//            Route::post('/order/{type}', 'OrdersController@order')->name('orders.order');
//        });
//    });
});
