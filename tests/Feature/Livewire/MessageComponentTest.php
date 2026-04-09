<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Message as MessageComponent;
use App\Models\Application;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageAddedAdmin;
use App\Notifications\MessageAddedUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class MessageComponentTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function message_component_renders_successfully()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $message = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Testnachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->assertSuccessful();
    }

    /** @test */
    public function owner_can_edit_own_message()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $message = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Ursprüngliche Nachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->set('isEditing', true)
            ->set('body', 'Bearbeitete Nachricht')
            ->call('editMessage')
            ->assertHasNoErrors()
            ->assertSet('isEditing', false);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'body' => 'Bearbeitete Nachricht',
        ]);
    }

    /** @test */
    public function user_cannot_edit_another_users_message()
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $owner->id]);
        $message = Message::create([
            'user_id' => $owner->id,
            'application_id' => $application->id,
            'body' => 'Fremde Nachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->set('body', 'Hacked message')
            ->call('editMessage');

        // Message body should not have been changed
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'body' => 'Fremde Nachricht',
        ]);
    }

    /** @test */
    public function edit_message_validates_minimum_length()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $message = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Testnachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->set('isEditing', true)
            ->set('body', 'Hi')
            ->call('editMessage')
            ->assertHasErrors(['body']);
    }

    /** @test */
    public function owner_can_delete_own_message()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $message = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Zu löschende Nachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->call('deleteMessage');

        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    }

    /** @test */
    public function user_cannot_delete_another_users_message()
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $owner->id]);
        $message = Message::create([
            'user_id' => $owner->id,
            'application_id' => $application->id,
            'body' => 'Fremde Nachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->call('deleteMessage');

        // Message should still exist
        $this->assertDatabaseHas('messages', ['id' => $message->id, 'deleted_at' => null]);
    }

    /** @test */
    public function user_can_reply_to_main_message()
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true, 'email_verified_at' => now()]);
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $message = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Hauptnachricht',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->set('body', 'Antwort auf Hauptnachricht')
            ->call('postReply')
            ->assertHasNoErrors()
            ->assertSet('body', '');

        $this->assertDatabaseHas('messages', [
            'application_id' => $application->id,
            'body' => 'Antwort auf Hauptnachricht',
            'main_message_id' => $message->id,
        ]);
    }

    /** @test */
    public function cannot_reply_to_a_reply()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $mainMessage = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Hauptnachricht',
        ]);
        $reply = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Erste Antwort',
            'main_message_id' => $mainMessage->id,
        ]);

        Livewire::test(MessageComponent::class, ['message' => $reply])
            ->set('body', 'Antwort auf Antwort')
            ->call('postReply');

        // Should not create a nested reply
        $this->assertDatabaseMissing('messages', [
            'body' => 'Antwort auf Antwort',
        ]);
    }

    /** @test */
    public function is_editing_populates_body_with_current_message()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $message = Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Nachrichtentext',
        ]);

        Livewire::test(MessageComponent::class, ['message' => $message])
            ->set('isEditing', true)
            ->assertSet('body', 'Nachrichtentext');
    }
}
