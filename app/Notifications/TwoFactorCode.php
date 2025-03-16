<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TwoFactorCode extends Notification
{
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
        Log::info('TwoFactorCode via method called', [
            'user_id' => $notifiable->id,
            'has_code' => !empty($notifiable->two_factor_code)
        ]);
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        // Log the state before building the email
        Log::info('Starting toMail method', [
            'user_id' => $notifiable->id,
            'code' => $notifiable->two_factor_code,
            'locale' => app()->getLocale(),
            'time' => now()->toDateTimeString()
        ]);

        // Ensure we have a code
        if (empty($notifiable->two_factor_code)) {
            Log::error('Two factor code is empty in toMail', [
                'user_id' => $notifiable->id,
                'time' => now()->toDateTimeString()
            ]);
            // Generate a new code if missing
            $notifiable->generateTwoFactorCode();
            $notifiable->refresh();
            Log::info('Generated new code', [
                'user_id' => $notifiable->id,
                'new_code' => $notifiable->two_factor_code
            ]);
        }

        $message = (new MailMessage())
            ->subject(__('notify.two_factor'))
            ->greeting(__('notify.greeting'))
            ->line(__('notify.two_factor_line1', ['code' => $notifiable->two_factor_code]))
            ->action(__('notify.two_factor_action'), route('verify.index', app()->getLocale()))
            ->line(__('notify.two_factor_line2'))
            ->line(__('notify.two_factor_line3'));

        // Log the final state
        Log::info('Email built successfully', [
            'user_id' => $notifiable->id,
            'final_code' => $notifiable->two_factor_code,
            'time' => now()->toDateTimeString()
        ]);

        return $message;
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
