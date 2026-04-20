<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Address;
use App\Models\DocumentHash;
use App\Models\Login;
use App\Models\Message;
use App\Models\User;
use App\Notifications\VerifyEmail;
use App\Services\FraudDetector;
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
        // Fraud detection — wrapped so errors never break user flows
        $fraudGuard = function (callable $check) {
            return function ($model) use ($check) {
                try { $check($model); } catch (\Throwable $e) {
                    Log::error('FraudDetector error: ' . $e->getMessage(), ['model' => get_class($model), 'id' => $model->id ?? null]);
                }
            };
        };

        DocumentHash::created($fraudGuard(fn($m) => FraudDetector::onDocumentHash($m)));
        Login::created($fraudGuard(fn($m) => FraudDetector::onLogin($m)));
        Account::saved($fraudGuard(fn($m) => FraudDetector::onAccount($m)));
        Address::saved($fraudGuard(fn($m) => FraudDetector::onAddress($m)));
        User::created($fraudGuard(fn($m) => FraudDetector::onUser($m)));
        User::updated(function ($user) use ($fraudGuard) {
            if ($user->wasChanged(['phone', 'mobile', 'phone_inst', 'soz_vers_nr'])) {
                $fraudGuard(fn($m) => FraudDetector::onUser($m))($user);
            }
        });

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
