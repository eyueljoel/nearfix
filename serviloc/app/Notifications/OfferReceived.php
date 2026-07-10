<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OfferReceived extends Notification
{
    use Queueable;

    public function __construct(public Offer $offer) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'offer_received',
            'title'              => 'New Offer Received',
            'message'            => "{$this->offer->provider->name} sent an offer of \${$this->offer->price} on your request \"{$this->offer->serviceRequest->title}\".",
            'action_url'         => route('customer.offers'),
            'offer_id'           => $this->offer->id,
            'service_request_id' => $this->offer->service_request_id,
            'provider_name'      => $this->offer->provider->name,
            'price'              => $this->offer->price,
        ];
    }
}
