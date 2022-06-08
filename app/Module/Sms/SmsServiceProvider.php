<?php

namespace App\Module\Sms;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/smsru.php',
            'smsru'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/Config/smspilot.php',
            'smspilot'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
