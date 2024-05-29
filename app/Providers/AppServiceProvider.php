<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::ignoreMigrations();
        Cashier::useSubscriptionModel(Subscription::class);

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('viewHorizon', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('viewTelescope', function (User $user) {
            return $user->isAdmin();
        });

        if (env('APP_SCHEME') == 'https') {
            URL::forceScheme('https');
        }else{
            URL::forceScheme('http');
        }
    }
}
