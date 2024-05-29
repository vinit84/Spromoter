<?php

namespace App\Providers;

use App\Events\ReviewCreated;
use App\Listeners\AfterImportListener;
use App\Listeners\ReviewCreatedListener;
use App\Listeners\StripeWebhookHandledListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Cashier\Events\WebhookHandled;
use Maatwebsite\Excel\Events\AfterImport;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Shopify\ShopifyExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SocialiteWasCalled::class => [
            ShopifyExtendSocialite::class.'@handle',
        ],

        /*WebhookReceived::class => [

        ],*/

        WebhookHandled::class => [
            StripeWebhookHandledListener::class,
        ],

        ReviewCreated::class => [
            ReviewCreatedListener::class,
        ],

        AfterImport::class => [
            AfterImportListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
