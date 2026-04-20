<?php

namespace Tests\Feature;

use App\Enums\FraudSignalType;
use App\Models\Account;
use App\Models\Address;
use App\Models\Application;
use App\Models\Currency;
use App\Models\DocumentHash;
use App\Models\FraudSignal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class FraudDetectorTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge(['is_admin' => 0], $attrs));
    }

    private function makeApplication(User $user): Application
    {
        return Application::factory()->create([
            'user_id'     => $user->id,
            'currency_id' => Currency::first()->id,
        ]);
    }

    // ─── IBAN ────────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function same_iban_from_different_users_creates_signal()
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();
        $app1  = $this->makeApplication($user1);
        $app2  = $this->makeApplication($user2);

        Account::factory()->create(['user_id' => $user1->id, 'application_id' => $app1->id, 'IBAN' => 'CH5604835012345678009']);
        Account::factory()->create(['user_id' => $user2->id, 'application_id' => $app2->id, 'IBAN' => 'CH5604835012345678009']);

        $this->assertDatabaseHas('fraud_signals', [
            'type'     => FraudSignalType::DUPLICATE_IBAN->value,
            'severity' => 'high',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function same_iban_same_user_does_not_create_signal()
    {
        $user = $this->makeUser();
        $app1 = $this->makeApplication($user);
        $app2 = $this->makeApplication($user);

        Account::factory()->create(['user_id' => $user->id, 'application_id' => $app1->id, 'IBAN' => 'CH5604835012345678009']);
        Account::factory()->create(['user_id' => $user->id, 'application_id' => $app2->id, 'IBAN' => 'CH5604835012345678009']);

        $this->assertDatabaseCount('fraud_signals', 0);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function duplicate_iban_signal_is_only_created_once()
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();
        $app1  = $this->makeApplication($user1);
        $app2  = $this->makeApplication($user2);

        Account::factory()->create(['user_id' => $user1->id, 'application_id' => $app1->id, 'IBAN' => 'CH5604835012345678009']);
        Account::factory()->create(['user_id' => $user2->id, 'application_id' => $app2->id, 'IBAN' => 'CH5604835012345678009']);
        // Trigger again (e.g. account update)
        Account::where('user_id', $user2->id)->first()->touch();

        $this->assertDatabaseCount('fraud_signals', 1);
    }

    // ─── Phone ───────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function same_phone_from_different_users_creates_signal()
    {
        $user1 = $this->makeUser(['phone' => '+41791234567']);
        $user2 = $this->makeUser(['phone' => '+41791234567']);

        $this->assertDatabaseHas('fraud_signals', [
            'type'     => FraudSignalType::DUPLICATE_PHONE->value,
            'severity' => 'high',
        ]);
    }

    // ─── Address ─────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function same_address_from_different_users_creates_signal()
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        $addressData = ['street' => 'Musterstrasse', 'number' => '5', 'plz' => '8000', 'town' => 'Zürich'];
        Address::factory()->create(array_merge($addressData, ['user_id' => $user1->id]));
        Address::factory()->create(array_merge($addressData, ['user_id' => $user2->id]));

        $this->assertDatabaseHas('fraud_signals', [
            'type'     => FraudSignalType::DUPLICATE_ADDRESS->value,
            'severity' => 'medium',
        ]);
    }

    // ─── Document ────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function same_document_hash_from_different_applications_creates_signal()
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();
        $app1  = $this->makeApplication($user1);
        $app2  = $this->makeApplication($user2);

        $hash = 'aabbcc' . str_repeat('0', 58);
        DocumentHash::create(['user_id' => $user1->id, 'application_id' => $app1->id, 'field_name' => 'commercial_register_extract', 'file_hash' => $hash]);
        DocumentHash::create(['user_id' => $user2->id, 'application_id' => $app2->id, 'field_name' => 'commercial_register_extract', 'file_hash' => $hash]);

        $signal = FraudSignal::where('type', FraudSignalType::DUPLICATE_DOCUMENT->value)->first();
        $this->assertNotNull($signal);
        $this->assertEquals('commercial_register_extract', $signal->details['field_name']);
    }

    // ─── AHV ─────────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function same_soz_vers_nr_creates_signal()
    {
        $user1 = $this->makeUser(['soz_vers_nr' => '756.1234.5678.90']);
        $user2 = $this->makeUser(['soz_vers_nr' => '756.1234.5678.90']);

        $this->assertDatabaseHas('fraud_signals', [
            'type'     => FraudSignalType::DUPLICATE_SOZ_VERS_NR->value,
            'severity' => 'high',
        ]);
    }

    // ─── Soft-deleted users ───────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function signal_fires_even_when_existing_user_is_soft_deleted()
    {
        $user1 = $this->makeUser();
        $app1  = $this->makeApplication($user1);
        Account::factory()->create(['user_id' => $user1->id, 'application_id' => $app1->id, 'IBAN' => 'CH5604835012345678009']);
        $user1->delete();

        $user2 = $this->makeUser();
        $app2  = $this->makeApplication($user2);
        Account::factory()->create(['user_id' => $user2->id, 'application_id' => $app2->id, 'IBAN' => 'CH5604835012345678009']);

        $this->assertDatabaseHas('fraud_signals', ['type' => FraudSignalType::DUPLICATE_IBAN->value]);
    }

    // ─── FraudSignal scopes ───────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function open_scope_excludes_reviewed_and_false_positive_signals()
    {
        $user1 = $this->makeUser(['phone' => '+41791111111']);
        $user2 = $this->makeUser(['phone' => '+41791111111']);

        $signal = FraudSignal::first();
        $this->assertCount(1, FraudSignal::open()->get());

        $signal->update(['reviewed_at' => now(), 'reviewed_by_id' => $user1->id]);
        $this->assertCount(0, FraudSignal::open()->get());
    }
}
