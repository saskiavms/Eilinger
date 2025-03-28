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
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
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

    /**
     * Handle a notification failure.
     */
    public function failed(\Exception $e, $notifiable)
    {
        Log::error('Email notification failed', [
            'error' => $e->getMessage(),
            'recipient' => $notifiable->email,
            'bounce_type' => $e->getCode(),
            'user_id' => $notifiable->id
        ]);
    }
}
