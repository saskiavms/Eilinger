<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email : The email address to send the test to}';
    protected $description = 'Send a test email to verify mail settings';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Testing mail configuration...');
        $this->info('Mail settings:');
        $this->info('Driver: ' . config('mail.default'));
        $this->info('Host: ' . config('mail.mailers.smtp.host'));
        $this->info('Port: ' . config('mail.mailers.smtp.port'));
        $this->info('From Address: ' . config('mail.from.address'));
        $this->info('Encryption: ' . config('mail.mailers.smtp.encryption'));

        try {
            Mail::raw('Test email from Laravel at ' . now(), function ($message) use ($email) {
                $message->to($email)
                    ->subject('Mail Settings Test');
            });

            $this->info('Test email sent successfully to ' . $email);
            Log::info('Test mail sent successfully', ['to' => $email]);
        } catch (\Exception $e) {
            $this->error('Failed to send test email:');
            $this->error($e->getMessage());
            Log::error('Test mail failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
