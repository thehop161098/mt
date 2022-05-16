<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

class RefundServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Core\Repositories\Contracts\RefundInterface',
            'Core\Repositories\Eloquents\RefundRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\SettingRefundInterface',
            'Core\Repositories\Eloquents\SettingRefundRepository'
        );
    }
}
