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
            'locale' => app()->getLocale()
        ]);

        if (empty($notifiable->two_factor_code)) {
            Log::error('Two factor code is empty', ['user_id' => $notifiable->id]);
        }

        return (new MailMessage())
            ->subject(__('notify.two_factor'))
            ->greeting(__('notify.greeting'))
            ->line(__('notify.two_factor_line1', ['code' => $notifiable->two_factor_code]))
            ->action(__('notify.two_factor_action'), route('verify.index', app()->getLocale()))
            ->line(__('notify.two_factor_line2'))
            ->line(__('notify.two_factor_line3'));
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
