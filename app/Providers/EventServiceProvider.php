<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\InstantPushNotification;
use App\Events\InstantMailNotification;
use App\Events\PaymentRefund;
use App\Listeners\SendInstantPushNotification;
use App\Listeners\SendInstantMailNotification;
use App\Listeners\PaymentRefundProcessed;
use App\Events\InstantWalletUpdate;
use App\Listeners\WalletUpdate;

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
        InstantPushNotification::class => [
            SendInstantPushNotification::class
        ],
        InstantMailNotification::class => [
            SendInstantMailNotification::class
        ],
        InstantWalletUpdate::class => [
            WalletUpdate::class
        ],
        PaymentRefund::class => [
            PaymentRefundProcessed::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
