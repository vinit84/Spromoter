<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        View::composer(['layouts.admin.partials.navbar', 'layouts.user.partials.navbar'], function ($view) {
            $languages = Cache::remember('languages', 60 * 60 * 24, function () {
                return Language::whereIsActive(1)->get();
            });

            $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
            $notifications = auth()->user()->notifications()->limit(5)->get();

            $view->with([
                'languages' => $languages,
                'unreadNotificationsCount' => $unreadNotificationsCount,
                'notifications' => $notifications
            ]);
        });
    }
}
