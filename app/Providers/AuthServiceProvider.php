<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Module\Customer\Models\Courier' => 'App\Module\Operator\Policies\CourierPolicy',
        'App\Module\Customer\Models\Customer' => 'App\Module\Operator\Policies\CustomerPolicy',
        'App\Module\Customer\Models\Order' => 'App\Module\Operator\Policies\OrderPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
