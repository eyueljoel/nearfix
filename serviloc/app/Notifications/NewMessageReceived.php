<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageReceived extends Notification
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $preview = \Illuminate\Support\Str::limit($this->message->body, 200);
        $sender  = $this->message->sender;

        return (new MailMessage)
            ->subject("New Message from {$sender->name} — ServiLoc")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have a new message on ServiLoc.")
            ->line("---")
            ->line("**From:** {$sender->name}")
            ->line("**Message:** {$preview}")
            ->line("---")
            ->action('Read & Reply', route('messages.show', $this->message->service_request_id))
            ->line("Log in to read the full message and reply.")
            ->salutation("— The ServiLoc Team");
    }

    public function toArray(object $notifiable): array
    {
        $preview = \Illuminate\Support\Str::limit($this->message->body, 60);

        return [
            'type'               => 'new_message',
            'title'              => 'New Message',
            'message'            => "{$this->message->sender->name} sent you a message: \"{$preview}\"",
            'action_url'         => route('messages.show', $this->message->service_request_id),
            'message_id'         => $this->message->id,
            'service_request_id' => $this->message->service_request_id,
            'sender_name'        => $this->message->sender->name,
        ];
    }
}
