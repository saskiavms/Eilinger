<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;
use App\Notifications\VerifyEmail;

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
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(NotificationSending::class, function ($event) {
            if ($event->notification instanceof VerifyEmail) {
                try {
                    $context = [
                        'user_id' => optional($event->notifiable)->id ?? null,
                        'email' => optional($event->notifiable)->email ?? null,
                        'channels' => $event->channels ?? null,
                    ];
                    if (app()->runningInConsole()) {
                        try {
                            fwrite(STDOUT, '[verify] sending ' . json_encode($context) . PHP_EOL);
                        } catch (\Throwable $ignore) {
                        }
                    } else {
                        Log::info('VerifyEmail sending', $context);
                    }
                } catch (\Throwable $t) {
                }
            }
        });

        Event::listen(NotificationSent::class, function ($event) {
            if ($event->notification instanceof VerifyEmail) {
                try {
                    $context = [
                        'user_id' => optional($event->notifiable)->id ?? null,
                        'email' => optional($event->notifiable)->email ?? null,
                        'channel' => $event->channel ?? null,
                    ];
                    if (app()->runningInConsole()) {
                        try {
                            fwrite(STDOUT, '[verify] sent ' . json_encode($context) . PHP_EOL);
                        } catch (\Throwable $ignore) {
                        }
                    } else {
                        Log::info('VerifyEmail sent', $context);
                    }
                } catch (\Throwable $t) {
                }
            }
        });

        Event::listen(NotificationFailed::class, function ($event) {
            if ($event->notification instanceof VerifyEmail) {
                try {
                    $context = [
                        'user_id' => optional($event->notifiable)->id ?? null,
                        'email' => optional($event->notifiable)->email ?? null,
                        'channel' => $event->channel ?? null,
                    ];
                    if (app()->runningInConsole()) {
                        try {
                            fwrite(STDERR, '[verify] failed ' . json_encode($context) . PHP_EOL);
                        } catch (\Throwable $ignore) {
                        }
                    } else {
                        Log::warning('VerifyEmail failed', $context);
                    }
                } catch (\Throwable $t) {
                }
            }
        });
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
