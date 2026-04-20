<?php

namespace App\Notifications;

use App\Models\FraudSignal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FraudSignalDetected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private FraudSignal $signal) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userA = $this->signal->user;
        $userB = $this->signal->relatedUser;

        $nameA = trim(($userA?->firstname ?? '') . ' ' . ($userA?->lastname ?? '') . ' ' . ($userA?->name_inst ?? ''));
        $nameB = trim(($userB?->firstname ?? '') . ' ' . ($userB?->lastname ?? '') . ' ' . ($userB?->name_inst ?? ''));

        return (new MailMessage)
            ->subject(__('notify.fraud_signal_subject'))
            ->greeting(__('notify.greeting'))
            ->line(__('notify.fraud_signal_line1', ['type' => $this->signal->type->label()]))
            ->line(__('notify.fraud_signal_line2', ['name' => $nameA, 'email' => $userA?->email ?? '—']))
            ->line(__('notify.fraud_signal_line3', ['name' => $nameB, 'email' => $userB?->email ?? '—']))
            ->action(__('notify.fraud_signal_action'), route('admin_fraud_signals', app()->getLocale()));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'fraud_signal_id' => $this->signal->id,
            'type'            => $this->signal->type->value,
            'url'             => route('admin_fraud_signals', app()->getLocale()),
        ];
    }
}
