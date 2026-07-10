<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OfferStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Offer $offer) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status  = $this->offer->status; // 'accepted' or 'rejected'
        $title   = $status === 'accepted' ? '🎉 Offer Accepted!' : 'Offer Rejected';
        $message = $status === 'accepted'
            ? "Your offer of \${$this->offer->price} on \"{$this->offer->serviceRequest->title}\" was accepted. You can now message the customer."
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
