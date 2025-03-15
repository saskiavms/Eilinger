<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TwoFactorCode extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        Log::info('TwoFactorCode notification instantiated');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        Log::info('TwoFactorCode via method called', ['user_id' => $notifiable->id]);
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        Log::info('TwoFactorCode toMail method called', [
            'user_id' => $notifiable->id,
            'code' => $notifiable->two_factor_code,
            'locale' => app()->getLocale(),
            'mail_config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'from_address' => config('mail.from.address'),
                'encryption' => config('mail.mailers.smtp.encryption')
            ]
        ]);

        try {
            $message = (new MailMessage())
                ->subject(__('notify.two_factor'))
                ->greeting(__('notify.greeting'))
                ->line(__('notify.two_factor_line1', ['code' => $notifiable->two_factor_code]))
                ->action(__('notify.two_factor_action'), route('verify.index', app()->getLocale()))
                ->line(__('notify.two_factor_line2'))
                ->line(__('notify.two_factor_line3'));

            Log::info('TwoFactorCode email message built successfully', [
                'to' => $notifiable->email,
                'subject' => __('notify.two_factor')
            ]);

            return $message;
        } catch (\Exception $e) {
            Log::error('Error building 2FA email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Handle a failed notification.
     */
    public function failed(\Exception $e)
    {
        Log::error('TwoFactorCode notification failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
