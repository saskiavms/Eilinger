<?php

namespace App\Providers;

use App\Models\Message;
use App\Notifications\VerifyEmail;
use App\Policies\MessagePolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Blade directive
        Blade::directive('convert', function ($expression) {
            return "<?php echo \Number::currency($expression); ?>";
        });

        // Policies (from AuthServiceProvider)
        Gate::policy(Message::class, MessagePolicy::class);

        // Rate limiting (from RouteServiceProvider)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Events (from EventServiceProvider)
        Event::listen(Registered::class, SendEmailVerificationNotification::class);

        Event::listen(NotificationSending::class, function ($event) {
            if ($event->notification instanceof VerifyEmail) {
                try {
                    $context = [
                        'user_id' => optional($event->notifiable)->id ?? null,
                        'email'   => optional($event->notifiable)->email ?? null,
                        'channels' => $event->channels ?? null,
                    ];
                    if (app()->runningInConsole()) {
                        fwrite(STDOUT, '[verify] sending ' . json_encode($context) . PHP_EOL);
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
                        'email'   => optional($event->notifiable)->email ?? null,
                        'channel' => $event->channel ?? null,
                    ];
                    if (app()->runningInConsole()) {
                        fwrite(STDOUT, '[verify] sent ' . json_encode($context) . PHP_EOL);
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
                        'email'   => optional($event->notifiable)->email ?? null,
                        'channel' => $event->channel ?? null,
                    ];
                    if (app()->runningInConsole()) {
                        fwrite(STDERR, '[verify] failed ' . json_encode($context) . PHP_EOL);
                    } else {
                        Log::warning('VerifyEmail failed', $context);
                    }
                } catch (\Throwable $t) {
                }
            }
        });
    }
}
