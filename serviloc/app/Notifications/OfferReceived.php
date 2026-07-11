<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferReceived extends Notification
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
        $offer   = $this->offer;
        $request = $offer->serviceRequest;
        $provider = $offer->provider;

        return (new MailMessage)
            ->subject("New Offer on \"{$request->title}\" — ServiLoc")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have received a new offer on your service request!")
            ->line("---")
            ->line("**Service:** {$request->title}")
            ->line("**Provider:** {$provider->name}")
            ->line("**Offer Price:** ETB " . number_format($offer->price, 2))
            ->line("**Provider's Message:** " . \Illuminate\Support\Str::limit($offer->message, 200))
            ->line("---")
            ->action('Review Offer', route('customer.offers'))
            ->line("Log in to accept, decline, or compare offers from other providers.")
            ->salutation("— The ServiLoc Team");
    }

    // ── Database ─────────────────────────────────────────────────────

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'offer_received',
            'title'              => 'New Offer Received',
            'message'            => "{$this->offer->provider->name} sent an offer of ETB {$this->offer->price} on your request \"{$this->offer->serviceRequest->title}\".",
            'action_url'         => route('customer.offers'),
            'offer_id'           => $this->offer->id,
            'service_request_id' => $this->offer->service_request_id,
            'provider_name'      => $this->offer->provider->name,
            'price'              => $this->offer->price,
        ];
    }
}
