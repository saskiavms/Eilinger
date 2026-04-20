<?php

namespace App\Services;

use App\Enums\FraudSignalType;
use App\Models\Account;
use App\Models\Address;
use App\Models\DocumentHash;
use App\Models\FraudSignal;
use App\Models\Login;
use App\Models\User;
use App\Notifications\FraudSignalDetected;
use Illuminate\Support\Facades\Notification;

class FraudDetector
{
    public static function onDocumentHash(DocumentHash $hash): void
    {
        DocumentHash::where('file_hash', $hash->file_hash)
            ->where('id', '!=', $hash->id)
            ->get()
            ->each(fn($other) => self::signal(
                FraudSignalType::DUPLICATE_DOCUMENT,
                $hash->user_id,
                $other->user_id,
                $hash->application_id,
                $other->application_id,
                ['field_name' => $hash->field_name],
            ));
    }

    public static function onAccount(Account $account): void
    {
        if (!$account->IBAN) return;

        Account::withTrashed()
            ->where('IBAN', $account->IBAN)
            ->where('user_id', '!=', $account->user_id)
            ->get()
            ->each(fn($other) => self::signal(
                FraudSignalType::DUPLICATE_IBAN,
                $account->user_id,
                $other->user_id,
                $account->application_id,
                $other->application_id,
            ));
    }

    public static function onAddress(Address $address): void
    {
        if (!$address->street || !$address->plz) return;

        Address::withTrashed()
            ->where('street', $address->street)
            ->where('number', $address->number)
            ->where('plz', $address->plz)
            ->where('user_id', '!=', $address->user_id)
            ->get()
            ->each(fn($other) => self::signal(
                FraudSignalType::DUPLICATE_ADDRESS,
                $address->user_id,
                $other->user_id,
            ));
    }

    public static function onUser(User $user): void
    {
        foreach (['phone', 'mobile', 'phone_inst'] as $field) {
            if (!$user->$field) continue;

            User::withTrashed()
                ->where($field, $user->$field)
                ->where('id', '!=', $user->id)
                ->get()
                ->each(fn($other) => self::signal(
                    FraudSignalType::DUPLICATE_PHONE,
                    $user->id,
                    $other->id,
                    details: ['field' => $field],
                ));
        }

        if ($user->soz_vers_nr) {
            User::withTrashed()
                ->where('soz_vers_nr', $user->soz_vers_nr)
                ->where('id', '!=', $user->id)
                ->get()
                ->each(fn($other) => self::signal(
                    FraudSignalType::DUPLICATE_SOZ_VERS_NR,
                    $user->id,
                    $other->id,
                ));
        }
    }

    public static function onLogin(Login $login): void
    {
        $ip = $login->ip_address;
        if (!$ip) return;

        // IPs are encrypted — must compare in PHP after decryption
        Login::withTrashed()
            ->where('id', '!=', $login->id)
            ->where('user_id', '!=', $login->user_id)
            ->get(['id', 'user_id', 'ip_address'])
            ->filter(fn($other) => $other->ip_address === $ip)
            ->unique('user_id')
            ->each(fn($other) => self::signal(
                FraudSignalType::DUPLICATE_IP,
                $login->user_id,
                $other->user_id,
            ));
    }

    public static function signal(
        FraudSignalType $type,
        ?int $userId,
        ?int $relatedUserId,
        ?int $applicationId = null,
        ?int $relatedApplicationId = null,
        array $details = [],
    ): void {
        // Never create signals involving admin accounts
        if ($userId && User::withTrashed()->where('id', $userId)->value('is_admin')) return;
        if ($relatedUserId && User::withTrashed()->where('id', $relatedUserId)->value('is_admin')) return;

        if ($userId && $relatedUserId) {
            $exists = FraudSignal::where('type', $type->value)
                ->where(function ($q) use ($userId, $relatedUserId) {
                    $q->where(fn($q) => $q->where('user_id', $userId)->where('related_user_id', $relatedUserId))
                      ->orWhere(fn($q) => $q->where('user_id', $relatedUserId)->where('related_user_id', $userId));
                })
                ->exists();

            if ($exists) return;
        }

        $signal = FraudSignal::create([
            'type'                    => $type->value,
            'severity'                => $type->severity(),
            'user_id'                 => $userId,
            'related_user_id'         => $relatedUserId,
            'application_id'          => $applicationId,
            'related_application_id'  => $relatedApplicationId,
            'details'                 => $details ?: null,
        ]);

        if ($type->severity() === 'high') {
            $admins = User::where('is_admin', 1)->get();
            Notification::send($admins, new FraudSignalDetected($signal->load(['user', 'relatedUser'])));
        }
    }
}
