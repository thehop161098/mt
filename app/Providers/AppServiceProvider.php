<?php

namespace App\Providers;

use App\Mail\VerifyMail;
use Core\Repositories\Eloquents\AdvertisementRepository;
use Core\Repositories\Eloquents\DiscountRepository;
use Core\Repositories\Eloquents\HistoryWalletRepository;
use Core\Repositories\Eloquents\WheelRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Core\Repositories\SettingRepository;
use Illuminate\Auth\Notifications\VerifyEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        // Override the email notification for verifying email
        VerifyEmail::toMailUsing(function ($user) {
            return new VerifyMail($user);
        });
        try {
            config([
                'settings' => SettingRepository::getSetting()
                    ->keyBy('key')// key every setting by its name
                    ->transform(function ($setting) {
                        return $setting->value; // return only the value
                    })
                    ->toArray() // make it an array
            ]);
            config(['app.name' => config('settings')['defaultPageTitle']]);
            $discounts = DiscountRepository::getDiscounts();
            $ads = AdvertisementRepository::getAdvertisements();
            $ads = array_merge($ads->toArray(), $discounts->toArray());
            $wheels = WheelRepository::getWheels();
            $historyWheels = HistoryWalletRepository::getTopWheel();
            view()->share([
                'ads' => $ads,
                'wheels' => $wheels,
                'historyWheels' => $historyWheels
            ]);
        } catch (\Exception $e) {
            view()->share([
                'ads' => [],
                'wheels' => [],
                'historyWheels' => []
            ]);
        }

        Schema::defaultStringLength(191);
    }
}
