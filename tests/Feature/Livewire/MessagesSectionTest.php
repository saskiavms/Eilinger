<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Message as MessageComponent;
use App\Livewire\MessagesSection;
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

class MessagesSectionTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function messages_section_renders_successfully()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->assertSuccessful();
    }

    /** @test */
    public function user_can_post_a_message()
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true, 'email_verified_at' => now()]);
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->set('body', 'Ich habe eine Frage zu meinem Antrag.')
            ->call('postMessage')
            ->assertHasNoErrors()
            ->assertSet('body', '');

        $this->assertDatabaseHas('messages', [
            'application_id' => $application->id,
            'user_id' => $user->id,
            'body' => 'Ich habe eine Frage zu meinem Antrag.',
        ]);
    }

    /** @test */
    public function post_message_validates_minimum_length()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->set('body', 'Hi')
            ->call('postMessage')
            ->assertHasErrors(['body']);
    }

    /** @test */
    public function post_message_requires_body()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->call('postMessage')
            ->assertHasErrors(['body']);
    }

    /** @test */
    public function user_message_notifies_admins()
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true, 'email_verified_at' => now()]);
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->set('body', 'Bitte um Rückmeldung zu meinem Antrag.')
            ->call('postMessage');

        Notification::assertSentTo($admin, MessageAddedUser::class);
    }

    /** @test */
    public function admin_message_notifies_application_owner()
    {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => now(), 'is_admin' => false]);
        $application = Application::factory()->create(['user_id' => $user->id]);

        $this->createAndAuthenticateAdmin();

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->set('body', 'Bitte reichen Sie Unterlagen nach.')
            ->call('postMessage');

        Notification::assertSentTo($user, MessageAddedAdmin::class);
    }

    /** @test */
    public function shows_existing_messages_for_application()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);

        Message::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'body' => 'Eine bestehende Nachricht',
        ]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->assertSee('Eine bestehende Nachricht');
    }

    /** @test */
    public function does_not_show_messages_from_other_applications()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $otherApplication = Application::factory()->create(['user_id' => $user->id]);

        Message::create([
            'user_id' => $user->id,
            'application_id' => $otherApplication->id,
            'body' => 'Nachricht eines anderen Antrags',
        ]);

        Livewire::test(MessagesSection::class, ['application' => $application])
            ->assertDontSee('Nachricht eines anderen Antrags');
    }
}
