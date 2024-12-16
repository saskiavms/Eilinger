<?php

namespace App\Livewire;

use App\Models\Message as MessageModel;
use App\Models\User;
use App\Notifications\MessageAddedAdmin;
use App\Notifications\MessageAddedUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class Message extends Component
{
    use AuthorizesRequests;

    public MessageModel $message;

    #[Rule('required|min:3')]
    public $body = '';

    public $isReplying = false;
    public $isEditing = false;

    public function mount()
    {
        $this->dispatch('message-mounted', messageId: $this->message->id);
    }

    public function updatedIsEditing($isEditing)
    {
        if (!$isEditing) {
            return;
        }

        $this->body = $this->message->body;
    }

    #[Computed]
    public function canReply()
    {
        return !$this->message->main_message_id;
    }

    public function editMessage()
    {
        $this->authorize('update', $this->message);

        $this->validate();

        $this->message->update([
            'body' => $this->body
        ]);

        $this->isEditing = false;
    }

    public function deleteMessage()
    {
        $this->authorize('destroy', $this->message);

        $mainMessageId = $this->message->main_message_id;
        $this->message->delete();

        if ($mainMessageId) {
            // If this is a reply, refresh the parent message
            $this->dispatch('reply-deleted', messageId: $mainMessageId);
        } else {
            // If this is a main message, refresh the messages section
            $this->dispatch('message-deleted')->to('messages-section');
        }
    }

    public function postReply()
    {
        if (!$this->message->isMainMessage()) {
            return;
        }

        $this->validate();

        $newReply = MessageModel::create([
            'user_id' => auth()->user()->id,
            'application_id' => $this->message->application_id,
            'body' => $this->body,
            'main_message_id' => $this->message->id,
        ]);

        $this->reset('body');
        $this->isReplying = false;

        if (auth()->user()->is_admin) {
            $this->message->application->user->notify(new MessageAddedAdmin($newReply));
        } else {
            User::where('is_admin', 1)->each(function ($admin) use ($newReply) {
                $admin->notify(new MessageAddedUser($newReply));
            });
        }

        // Refresh the parent message to show the new reply
        $this->dispatch('reply-added', messageId: $this->message->id);
    }

    #[On('reply-deleted')]
    #[On('reply-added')]
    public function refreshReplies($messageId)
    {
        if ($this->message->id === $messageId) {
            $this->message->refresh();
        }
    }

    public function render()
    {
        return view('livewire.message');
    }
}
