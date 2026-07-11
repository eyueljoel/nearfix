<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Offer $offer) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // ── Email ────────────────────────────────────────────────────────

    public function toMail(object $notifiable): MailMessage
    {
        $offer    = $this->offer;
        $status   = $offer->status;
        $request  = $offer->serviceRequest;

        if ($status === 'accepted') {
            return (new MailMessage)
                ->subject("🎉 Your Offer Was Accepted — ServiLoc")
                ->greeting("Great news, {$notifiable->name}!")
                ->line("Your offer has been accepted by the customer.")
                ->line("---")
                ->line("**Service:** {$request->title}")
                ->line("**Your Price:** ETB " . number_format($offer->price, 2))
                ->line("**Customer:** {$request->user->name}")
                ->line("**Location:** {$request->location}")
                ->line("---")
                ->action('View Job & Message Customer', route('provider.offers'))
                ->line("The customer has been notified. You can now message them to coordinate the details.")
                ->salutation("— The ServiLoc Team");
        }

        // Rejected
        return (new MailMessage)
            ->subject("Your Offer Was Not Selected — ServiLoc")
            ->greeting("Hello {$notifiable->name},")
            ->line("Unfortunately, your offer was not selected for this request.")
            ->line("---")
            ->line("**Service:** {$request->title}")
            ->line("**Your Price:** ETB " . number_format($offer->price, 2))
            ->line("---")
            ->action('Browse New Requests', route('provider.requests'))
            ->line("Don't be discouraged — there are many more requests waiting for offers. Keep bidding!")
            ->salutation("— The ServiLoc Team");
    }

    // ── Database ─────────────────────────────────────────────────────

    public function toArray(object $notifiable): array
    {
        $status  = $this->offer->status;
        $title   = $status === 'accepted' ? '🎉 Offer Accepted!' : 'Offer Not Selected';
        $message = $status === 'accepted'
            ? "Your offer of ETB {$this->offer->price} on \"{$this->offer->serviceRequest->title}\" was accepted."
            : "Your offer on \"{$this->offer->serviceRequest->title}\" was not selected this time.";

        return [
            'type'               => 'offer_status_changed',
            'title'              => $title,
            'message'            => $message,
            'action_url'         => route('provider.offers'),
            'offer_id'           => $this->offer->id,
            'service_request_id' => $this->offer->service_request_id,
            'status'             => $status,
        ];
    }
}
