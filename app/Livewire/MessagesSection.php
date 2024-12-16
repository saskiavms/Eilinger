<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageAddedAdmin;
use App\Notifications\MessageAddedUser;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;

class MessagesSection extends Component
{
    use WithPagination;

    public Application $application;

    #[Rule('required|min:3')]
    public $body = '';

    #[On('message-deleted')]
    public function handleMessageDeleted()
    {
        // Force a refresh of the messages
        $this->reset('page');
    }

    public function render()
    {
        return view('livewire.messages-section', [
            'messages' => $this->application
                ->messages()
                ->with('user', 'replies.user', 'replies.replies')
                ->mainMessage()
                ->latest()
                ->paginate(5)
        ]);
    }

    public function postMessage()
    {
        $this->validate();

        $newMessage = Message::create([
            'user_id' => auth()->user()->id,
            'application_id' => $this->application->id,
            'body' => $this->body,
        ]);

        $this->reset('body');

        if (auth()->user()->is_admin) {
            $this->application->user->notify(new MessageAddedAdmin($newMessage));
        } else {
            User::where('is_admin', 1)->each(function ($admin) use ($newMessage) {
                $admin->notify(new MessageAddedUser($newMessage));
            });
        }
    }
}
