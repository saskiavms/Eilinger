<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Address;
use App\Models\DocumentHash;
use App\Models\Login;
use App\Models\User;
use App\Services\FraudDetector;
use Illuminate\Console\Command;

class ScanFraudSignals extends Command
{
    protected $signature = 'fraud:scan';
    protected $description = 'Scan existing data for fraud signals (run once after deployment)';

    public function handle(): int
    {
        $this->info('Scanning for fraud signals in existing data...');

        $this->scanUsers();
        $this->scanAccounts();
        $this->scanAddresses();
        $this->scanDocumentHashes();
        $this->scanLoginIps();

        $this->info('Done.');
        return self::SUCCESS;
    }

    private function scanUsers(): void
    {
        $count = User::withTrashed()->count();
        $this->line("  Users ({$count})...");
        User::withTrashed()->chunkById(100, function ($users) {
            foreach ($users as $user) {
                FraudDetector::onUser($user);
            }
        });
    }

    private function scanAccounts(): void
    {
        $count = Account::withTrashed()->count();
        $this->line("  Accounts ({$count})...");
        Account::withTrashed()->chunkById(100, function ($accounts) {
            foreach ($accounts as $account) {
                FraudDetector::onAccount($account);
            }
        });
    }

    private function scanAddresses(): void
    {
        $count = Address::withTrashed()->count();
        $this->line("  Addresses ({$count})...");
        Address::withTrashed()->chunkById(100, function ($addresses) {
            foreach ($addresses as $address) {
                FraudDetector::onAddress($address);
            }
        });
    }

    private function scanDocumentHashes(): void
    {
        $count = DocumentHash::count();
        $this->line("  Document hashes ({$count})...");
        DocumentHash::chunkById(100, function ($hashes) {
            foreach ($hashes as $hash) {
                FraudDetector::onDocumentHash($hash);
            }
        });
    }

    private function scanLoginIps(): void
    {
        $count = Login::withTrashed()->count();
        $this->line("  Login IPs ({$count}) — decrypting in PHP, may take a moment...");

        // Group by decrypted IP in PHP since they are encrypted in the DB
        $byIp = Login::withTrashed()
            ->get(['id', 'user_id', 'ip_address'])
            ->groupBy('ip_address');

        foreach ($byIp as $ip => $logins) {
            $uniqueUsers = $logins->pluck('user_id')->unique();
            if ($uniqueUsers->count() < 2) continue;

            // One representative login per user
            $uniqueUsers->each(function ($userId) use ($logins) {
                $login = $logins->firstWhere('user_id', $userId);
                FraudDetector::onLogin($login);
            });
        }
    }
}
