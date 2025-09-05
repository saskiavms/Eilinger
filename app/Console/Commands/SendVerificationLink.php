<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\URL;

class SendVerificationLink extends Command
{
    protected $signature = 'user:verify-email {email : User email address} {--now : Send synchronously (bypass queue)} {--locale= : Locale to use for link}';
    protected $description = 'Send a verification email to a user by email address (optionally synchronously) and print the signed URL for diagnostics.';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $sendNow = (bool) $this->option('now');
        $locale = $this->option('locale');

        // Force console-friendly logging to avoid storage permissions
        try {
            Config::set('logging.default', 'stderr');
        } catch (\Throwable $ignore) {
        }

        /** @var User|null $user */
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('User not found: ' . $email);
            return self::FAILURE;
        }

        if (!($user instanceof MustVerifyEmail)) {
            $this->warn('User model does not implement MustVerifyEmail; sending anyway.');
        }

        if ($user->hasVerifiedEmail()) {
            $this->info('User already verified. A link will still be sent on request.');
        }

        // Generate and show the signed URL that the notification will contain (for quick diagnostics)
        $linkLocale = $locale ?: app()->getLocale();
        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'locale' => $linkLocale,
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->line('Signed verification URL (diagnostic):');
        $this->line($signedUrl);

        try {
            if ($sendNow) {
                // Send synchronously (bypass queue)
                NotificationFacade::sendNow($user, new VerifyEmail());
                $this->info('Verification email sent synchronously to ' . $email);
            } else {
                // Normal queued notification
                $user->notify(new VerifyEmail());
                $this->info('Verification email queued for ' . $email);
            }
            try {
                fwrite(STDOUT, '[verify] manual trigger ' . json_encode(['user_id' => $user->id,'email' => $user->email,'now' => $sendNow,'locale' => $linkLocale]) . PHP_EOL);
            } catch (\Throwable $ignore) {
            }
        } catch (\Throwable $e) {
            $this->error('Failed to send verification email: ' . $e->getMessage());
            try {
                fwrite(STDERR, '[verify] manual trigger failed ' . json_encode(['user_id' => $user->id,'email' => $user->email,'error' => $e->getMessage()]) . PHP_EOL);
            } catch (\Throwable $ignore) {
            }
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
