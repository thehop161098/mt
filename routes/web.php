<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Auto-generated admin routes */

Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function () {
        // Route::prefix('admin-users')->name('admin-users/')->group(static function () {
        //     Route::get('/','AdminUsersController@index')->name('index');
        //     Route::get('/create','AdminUsersController@create')->name('create');
        //     Route::post('/','AdminUsersController@store')->name('store');
        //     Route::get('/{adminUser}/impersonal-login','AdminUsersController@impersonalLogin')->name('impersonal-login');
        //     Route::get('/{adminUser}/edit','AdminUsersController@edit')->name('edit');
        //     Route::post('/{adminUser}','AdminUsersController@update')->name('update');
        //     Route::delete('/{adminUser}','AdminUsersController@destroy')->name('destroy');
        //     Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        // });
        Route::get('/profile', 'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile', 'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password', 'ProfileController@editPassword')->name('edit-password');
        Route::post('/password', 'ProfileController@updatePassword')->name('update-password');

        // // Wallet
        Route::prefix('wallets')->name('wallets/')->group(static function () {
            Route::get('/', 'WalletsController@index')->name('wallet.index');
            Route::get('/create', 'WalletsController@create')->name('wallet.create');
            Route::post('/', 'WalletsController@store')->name('wallet.store');
            Route::get('/{wallet}/edit', 'WalletsController@edit')->name('wallet.edit');
            Route::post('/bulk-destroy', 'WalletsController@bulkDestroy')->name('wallet.bulkDestroy');
            Route::post('/{wallet}', 'WalletsController@update')->name('wallet.update');
            Route::delete('/{wallet}', 'WalletsController@destroy')->name('wallet.destroy');
            Route::post('/{wallet}/reset', 'WalletsController@reset')->name('wallet.reset');
            Route::get('/autoCreate', 'WalletsController@autoCreate')->name('wallet.autoCreate');
            Route::post('/{wallet}/autoTransfer', 'WalletsController@autoTransfer')->name('wallet.autoTransfer');
        });
        // Email templates
        Route::prefix('email-templates')->name('email-templates/')->group(static function () {
            Route::get('/', 'EmailTemplatesController@index')->name('email.index');
            Route::get('/create', 'EmailTemplatesController@create')->name('email.create');
            Route::post('/', 'EmailTemplatesController@store')->name('email.store');
            Route::get('/{emailTemplate}/edit', 'EmailTemplatesController@edit')->name('email.edit');
            Route::post('/bulk-destroy', 'EmailTemplatesController@bulkDestroy')->name('email.bulkDestroy');
            Route::post('/{emailTemplate}', 'EmailTemplatesController@update')->name('email.update');
            Route::delete('/{emailTemplate}', 'EmailTemplatesController@destroy')->name('email.destroy');
        });
        // User
        Route::prefix('users')->name('users/')->group(static function () {
            Route::get('/', 'UsersController@index')->name('index');
            // Route::get('/create','UsersController@create')->name('create');
            // Route::post('/','UsersController@store')->name('store');
            // Route::get('/{user}/edit','UsersController@edit')->name('edit');
            // Route::post('/{user}','UsersController@update')->name('update');
            Route::get('/reject/{id}', 'UsersController@reject')->name('users.reject');
            Route::get('/approve/{id}', 'UsersController@approve')->name('users.approve');
            Route::delete('/{user}', 'UsersController@destroy')->name('destroy');
            Route::post('/bulk-destroy', 'UsersController@bulkDestroy')->name('bulk-destroy');
        });
        // Coins
        Route::prefix('coins')->name('coins/')->group(static function () {
            Route::get('/', 'CoinsController@index')->name('coins.index');
            Route::get('/create', 'CoinsController@create')->name('coins.create');
            Route::post('/', 'CoinsController@store')->name('coins.store');
            Route::get('/{coin}/edit', 'CoinsController@edit')->name('coins.edit');
            Route::post('/bulk-destroy', 'CoinsController@bulkDestroy')->name('coins.bulk-destroy');
            Route::post('/{coin}', 'CoinsController@update')->name('coins.update');
            Route::delete('/{coin}', 'CoinsController@destroy')->name('coins.destroy');
        });
        // Levels
        Route::prefix('levels')->name('levels/')->group(static function () {
            Route::get('/', 'LevelsController@index')->name('levels.index');
            Route::get('/create', 'LevelsController@create')->name('levels.create');
            Route::post('/', 'LevelsController@store')->name('levels.store');
            Route::get('/{level}/edit', 'LevelsController@edit')->name('levels.edit');
            Route::post('/bulk-destroy', 'LevelsController@bulkDestroy')->name('levels.bulk-destroy');
            Route::post('/{level}', 'LevelsController@update')->name('levels.update');
            Route::delete('/{level}', 'LevelsController@destroy')->name('levels.destroy');
        });
        // setting refund
        Route::prefix('setting-refunds')->name('setting-refunds/')->group(static function () {
            Route::get('/', 'SettingRefundsController@index')->name('setting-refunds.index');
            Route::get('/create', 'SettingRefundsController@create')->name('setting-refunds.create');
            Route::post('/', 'SettingRefundsController@store')->name('setting-refunds.store');
            Route::get('/{settingRefund}/edit', 'SettingRefundsController@edit')->name('setting-refunds.edit');
            Route::post('/bulk-destroy', 'SettingRefundsController@bulkDestroy')->name('setting-refunds.bulk-destroy');
            Route::post('/{settingRefund}', 'SettingRefundsController@update')->name('setting-refunds.update');
            Route::delete('/{settingRefund}', 'SettingRefundsController@destroy')->name('setting-refunds.destroy');
        });
        // Summary Trade
        Route::prefix('summary-trades')->name('summary-trades/')->group(static function () {
            Route::get('/', 'SummaryTradesController@index')->name('summary-trades.index');
        });
        // Control Candles
//        Route::group(['prefix' => 'control-candles'], function () {
//            Route::get('/', 'ControlCandleController@index')->name('control-candles.index');
//            Route::post('/{id}/{type}', 'ControlCandleController@rangeCandle')->name('control-candles.rangeCandle');
//        });
        Route::prefix('control-candles')->name('control-candles/')->group(static function () {
            Route::get('/', 'ControlCandleController@index')->name('index');
            Route::post('/{id}/{type}', 'ControlCandleController@rangeCandle')->name('rangeCandle');
        });
        // Transfer
        Route::prefix('transfer')->name('transfer/')->group(static function () {
            Route::get('/', 'TransferController@index')->name('transfer.index');
        });
        /**
         * Settings
         */
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', [
                'as' => 'settings.index',
                'uses' => 'SettingsController@index',
            ]);
            Route::post('edit', [
                'as' => 'settings.store',
                'uses' => 'SettingsController@store',
            ]);
        });
        /**
         * History withdraw
         */
        Route::prefix('history-withdraws')->name('history-withdraws/')->group(static function () {
            Route::get('/', 'HistoryWithdrawController@index')->name('history-withdraw.index');
            Route::post('/edit', 'HistoryWithdrawController@ajaxChangeStatusWithdraw')->name('history-withdraw.store');
            Route::get('/{historyWithdraw}/edit', 'HistoryWithdrawController@edit')->name('history-withdraw.edit');
            Route::post('/{historyWithdraw}', 'HistoryWithdrawController@update')->name('history-withdraw.update');
        });

        Route::prefix('history-deposits')->name('history-deposits/')->group(static function () {
            Route::get('/', 'HistoryDepositController@index')->name('deposit.index');
        });
        // FAQ
        Route::prefix('faqs')->name('faqs/')->group(static function () {
            Route::get('/', 'FaqsController@index')->name('faq.index');
            Route::get('/create', 'FaqsController@create')->name('faq.create');
            Route::post('/', 'FaqsController@store')->name('faq.store');
            Route::get('/{faq}/edit', 'FaqsController@edit')->name('faq.edit');
            Route::post('/bulk-destroy', 'FaqsController@bulkDestroy')->name('faq.bulk-destroy');
            Route::post('/{faq}', 'FaqsController@update')->name('faq.update');
            Route::delete('/{faq}', 'FaqsController@destroy')->name('faq.destroy');
        });

        Route::prefix('phone-countries')->name('phone-countries/')->group(static function () {
            Route::get('/', 'PhoneCountriesController@index')->name('index');
            Route::get('/create', 'PhoneCountriesController@create')->name('create');
            Route::post('/', 'PhoneCountriesController@store')->name('store');
            Route::get('/{phoneCountry}/edit', 'PhoneCountriesController@edit')->name('edit');
            Route::post('/bulk-destroy', 'PhoneCountriesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{phoneCountry}', 'PhoneCountriesController@update')->name('update');
            Route::delete('/{phoneCountry}', 'PhoneCountriesController@destroy')->name('destroy');
        });

        Route::prefix('history-refunds')->name('history-refunds/')->group(static function () {
            Route::get('/', 'HistoryRefundController@index')->name('index');
            Route::get('/create', 'HistoryRefundController@create')->name('create');
            Route::post('/', 'HistoryRefundController@store')->name('store');
            Route::get('/{historyRefund}/edit', 'HistoryRefundController@edit')->name('edit');
            Route::post('/bulk-destroy', 'HistoryRefundController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{historyRefund}', 'HistoryRefundController@update')->name('update');
            Route::delete('/{historyRefund}', 'HistoryRefundController@destroy')->name('destroy');
        });

        Route::prefix('discounts')->name('discounts/')->group(static function () {
            Route::get('/', 'DiscountsController@index')->name('index');
            Route::get('/create', 'DiscountsController@create')->name('create');
            Route::post('/', 'DiscountsController@store')->name('store');
            Route::get('/{discount}/edit', 'DiscountsController@edit')->name('edit');
            Route::post('/bulk-destroy', 'DiscountsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{discount}', 'DiscountsController@update')->name('update');
            Route::delete('/{discount}', 'DiscountsController@destroy')->name('destroy');
        });

        Route::prefix('supports')->name('supports/')->group(static function () {
            Route::get('/', 'SupportsController@index')->name('index');
            Route::get('/create', 'SupportsController@create')->name('create');
            Route::post('/', 'SupportsController@store')->name('store');
            Route::get('/{support}/edit', 'SupportsController@edit')->name('edit');
            Route::post('/bulk-destroy', 'SupportsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{support}', 'SupportsController@update')->name('update');
            Route::delete('/{support}', 'SupportsController@destroy')->name('destroy');
        });

        Route::prefix('wheels')->name('wheels/')->group(static function () {
            Route::get('/', 'WheelsController@index')->name('index');
            Route::get('/create', 'WheelsController@create')->name('create');
            Route::post('/', 'WheelsController@store')->name('store');
            Route::get('/{wheel}/edit', 'WheelsController@edit')->name('edit');
            Route::post('/bulk-destroy', 'WheelsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{wheel}', 'WheelsController@update')->name('update');
            Route::delete('/{wheel}', 'WheelsController@destroy')->name('destroy');
        });

        Route::prefix('wheel-settings')->name('wheel-settings/')->group(static function () {
            Route::get('/', 'WheelSettingsController@index')->name('index');
            Route::get('/create', 'WheelSettingsController@create')->name('create');
            Route::post('/', 'WheelSettingsController@store')->name('store');
            Route::get('/{wheelSetting}/edit', 'WheelSettingsController@edit')->name('edit');
            Route::post('/bulk-destroy', 'WheelSettingsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{wheelSetting}', 'WheelSettingsController@update')->name('update');
            Route::delete('/{wheelSetting}', 'WheelSettingsController@destroy')->name('destroy');
        });

        Route::prefix('luckyWheelHistory')->name('luckyWheelHistory/')->group(static function () {
            Route::get('/', 'LuckyWheelHistoryController@index')->name('luckyWheelHistory.index');
        });

        Route::prefix('advertisements')->name('advertisements/')->group(static function () {
            Route::get('/', 'AdvertisementsController@index')->name('index');
            Route::get('/create', 'AdvertisementsController@create')->name('create');
            Route::post('/', 'AdvertisementsController@store')->name('store');
            Route::get('/{advertisement}/edit', 'AdvertisementsController@edit')->name('edit');
            Route::post('/bulk-destroy', 'AdvertisementsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{advertisement}', 'AdvertisementsController@update')->name('update');
            Route::delete('/{advertisement}', 'AdvertisementsController@destroy')->name('destroy');
        });

        Route::prefix('auto-bots')->name('auto-bots/')->group(static function () {
            Route::get('/', 'AutoBotsController@index')->name('index');
            Route::get('/create', 'AutoBotsController@create')->name('create');
            Route::post('/', 'AutoBotsController@store')->name('store');
            Route::get('/{autoBot}/edit', 'AutoBotsController@edit')->name('edit');
            Route::post('/bulk-destroy', 'AutoBotsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{autoBot}', 'AutoBotsController@update')->name('update');
            Route::delete('/{autoBot}', 'AutoBotsController@destroy')->name('destroy');
        });
    });
});


Route::group(['middleware' => ['auth', 'verified', '2fa']], function () {
    Route::get('/', 'TradingController@index')->name('home');
    Route::get('/testLucj', 'TestController@index')->name('test');

    // History
    Route::group(['prefix' => 'history'], function () {
        Route::get('/demo', 'HistoryController@demo')->name('history.demo');
        Route::get('/live', 'HistoryController@live')->name('history.live');
        Route::get('/promotion', 'HistoryController@promotion')->name('history.promotion');
        Route::get('/refund', 'HistoryController@refund')->name('history.refund');
    });
    // Page Dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', 'DashboardController@index')->name('dashboard.index');
        Route::post('/refund', 'DashboardController@postRefund')->name('refund.postRefund');
    });
    // Page Convert
    Route::group(['prefix' => 'convert'], function () {
        Route::get('/', 'ConvertController@index')->name('convert.index');
    });
    // Page Agency
    Route::group(['prefix' => 'affiliate-marketing'], function () {
        Route::get('/', 'AgencyController@index')->name('agency.index');
        Route::post('/', 'AgencyController@buyAgency')->name('agency.buyAgency');
    });
    // Commission
    Route::group(['prefix' => 'commission'], function () {
        Route::get('/daily', 'CommissionController@daily')->name('commission.daily');
        Route::get('/affiliate-marketing', 'CommissionController@agency')->name('commission.agency');
        Route::get('/master-ib', 'CommissionController@masterib')->name('commission.master-ib');
        Route::get('/imcome', 'CommissionController@imcome')->name('commission.imcome');
        Route::get('/bot', 'CommissionController@bot')->name('commission.bot');
    });
    // FAQ
    Route::group(['prefix' => 'faqs'], function () {
        Route::get('/', 'FaqsController@index')->name('faq.index');
    });
    // Profile User
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@edit')->name('user.edit');
        Route::post('/', 'UserController@update')->name('user.update');
        Route::post('/change-password', 'UserController@changePassword')->name('user.change-password');
        // 2FA
        Route::post('/generateSecret', 'UserController@generate2faSecret')->name('generate2faSecret');
        Route::post('/toggle2fa', 'UserController@toggle2fa')->name('toggle2fa');
        Route::post('/verify2fa', 'UserController@verify2fa')->name('verify2fa')->middleware('2fa');
        // update Identity Card
        Route::get('/identity-card', 'UserController@identityCard')->name('identityCard');
        Route::post('/identity-card', 'UserController@postIdentityCard')->name('postIdentityCard');
    });

    Route::group(['prefix' => 'auto-bots'], function () {
        Route::get('/', 'AutoBotController@index')->name('autoBot.index');
        Route::post('/buy/{botId}/{timeSelected}', 'AutoBotController@buy')->name('autoBot.buy');
        Route::post('/unLock/{id}', 'AutoBotController@unLock')->name('autoBot.unLock');
    });

    // Transactions
//    Route::group(['prefix' => 'transactions'], function () {
//        Route::get('step1', function () {
//            return view('frontend.transactions.step1');
//        });
//        Route::get('step2', function () {
//            return view('frontend.transactions.step2');
//        });
//        Route::get('step3', function () {
//            return view('frontend.transactions.step3');
//        });
//        Route::get('step4', function () {
//            return view('frontend.transactions.step4');
//        });
//    });

    // Ajax Route
    Route::namespace('Ajax')->group(static function () {
        Route::prefix('ajax')->group(static function () {
            Route::prefix('candles')->name('candles/')->group(static function () {
                Route::get('/getConfigs', 'CandlesController@index')->name('candles.index');
                Route::get('/getCandles/{coin}', 'CandlesController@getCandles')->name('candles.getCandles');
                Route::get('/getCirCleHistory/{coin}',
                    'CandlesController@getCirCleHistory')->name('candles.getCirCleHistory');
            });
            Route::prefix('walletGames')->name('walletGames/')->group(static function () {
                Route::get('/getAmountWalletGame',
                    'WalletGamesController@getAmountWalletGame')->name('walletGames.getAmountWalletGame');
                Route::get('/getWallets', 'WalletGamesController@getWallets')->name('walletGames.getWallets');
                Route::get('/getWalletSelected',
                    'WalletGamesController@getWalletSelected')->name('walletGames.getWalletSelected');
                Route::get('/changeWalletSelected/{type}',
                    'WalletGamesController@changeWalletSelected')->name('walletGames.changeWalletSelected');
            });

            Route::prefix('orders')->name('orders/')->group(static function () {
                Route::post('/order/{coin}/{wallet}/{type}', 'OrdersController@order')->name('orders.order');
                Route::get('/getOrdersInMinutes',
                    'OrdersController@getOrdersInMinutes')->name('orders.getOrdersInMinutes');
                Route::get('/getSummaryOrder/{isLast}',
                    'OrdersController@getSummaryOrder')->name('orders.getSummaryOrder');
            });

            Route::prefix('coins')->name('coins/')->group(static function () {
                Route::get('/getCoins', 'CoinsController@getCoins')->name('coins.getCoins');
            });

            Route::prefix('historyWallets')->name('historyWallets/')->group(static function () {
                Route::get('/readAll', 'HistoryWalletController@readAll')->name('historyWallets.readAll');
            });

            Route::prefix('luckyWheel')->name('luckyWheel/')->group(static function () {
                Route::get('/validSpin', 'LuckyWheelController@spin')->name('luckyWheel.spin');
            });
        });
    });

    Route::group(['prefix' => 'wallets'], function () {
        Route::get('/', [
            'as' => 'wallets.index',
            'uses' => 'WalletsController@index',
        ]);
        Route::get('/exchange', [
            'as' => 'wallets.exchange',
            'uses' => 'WalletsController@exchange',
        ]);
        Route::get('/deposit', [
            'as' => 'wallets.deposit',
            'uses' => 'WalletsController@deposit',
        ]);
        Route::get('/ajaxGetGasPrice', [
            'as' => 'wallets.getGasPrice',
            'uses' => 'WalletsController@ajaxGetGasPrice',
        ]);
        Route::post('withdraw', [
            'as' => 'wallets.withdraw',
            'uses' => 'WalletsController@withdraw',
        ]);
        Route::post('/ajaxInternalTransfer', [
            'as' => 'wallets.ajaxInternalTransfer',
            'uses' => 'WalletsController@ajaxInternalTransfer',
        ]);
        Route::get('/ajaxGetDataInternal', [
            'as' => 'wallets.ajaxGetDataInternal',
            'uses' => 'WalletsController@ajaxGetInternalWithdraw',
        ]);
        Route::get('/ajaxGetDataWithdraw', [
            'as' => 'wallets.ajaxGetDataWithdraw',
            'uses' => 'WalletsController@ajaxGetDataWithdraw',
        ]);
        Route::post('refill', [
            'as' => 'wallets.refill',
            'uses' => 'WalletsController@refill',
        ]);
    });
});


Route::group(['prefix' => 'confirm'], function () {
    Route::get('/user-withdraw/{id}', [
        'as' => 'wallets.user_confirm_withdraw',
        'uses' => 'WalletsController@userConfirmWithdraw',
    ]);
});

Route::prefix('support')->name('support/')->group(static function () {
    Route::get('/', 'SupportController@index')->name('index');
    Route::post('/', 'SupportController@index')->name('store');
});

Auth::routes(['verify' => true]);


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('history-bots')->name('history-bots/')->group(static function() {
            Route::get('/',                                             'HistoryBotsController@index')->name('index');
            Route::get('/create',                                       'HistoryBotsController@create')->name('create');
            Route::post('/',                                            'HistoryBotsController@store')->name('store');
            Route::get('/{historyBot}/edit',                            'HistoryBotsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'HistoryBotsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{historyBot}',                                'HistoryBotsController@update')->name('update');
            Route::delete('/{historyBot}',                              'HistoryBotsController@destroy')->name('destroy');
        });
    });
});
