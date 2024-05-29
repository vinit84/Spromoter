<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class UserViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.user.partials.navbar', function ($view) {
            $stores = auth()->user()->stores()->get();

            $view->with([
                'stores' => $stores,
            ]);
        });

        View::composer('layouts.user.partials.sidebar', function ($view) {
            $user = auth()->user();
            $totalOrders = $user->subscriptions()
                ->whereStoreId(activeStore()?->id)
                ->where(function ($query) {
                    $query->where('stripe_status', '=', 'active')
                        ->orWhere('stripe_status', '=', 'trialing');
                })->sum('total_orders');

            $usedOrders = $user->subscriptions()
                ->whereStoreId(activeStore()?->id)
                ->where(function ($query) {
                    $query->where('stripe_status', '=', 'active')
                        ->orWhere('stripe_status', '=', 'trialing');
                })->sum('used_orders');

            $view->with([
                'totalOrders' => $totalOrders,
                'usedOrders' => $usedOrders,
            ]);
        });
    }
}
