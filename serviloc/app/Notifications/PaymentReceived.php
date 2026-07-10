<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->payment->isReleased()) {
            return [
                'type'               => 'payment_released',
                'title'              => '💸 Payment Released!',
                'message'            => "ETB " . number_format($this->payment->amount, 2) . " has been released to you for \"{$this->payment->serviceRequest->title}\".",
                'action_url'         => route('payments.provider-history'),
                'payment_id'         => $this->payment->id,
                'service_request_id' => $this->payment->service_request_id,
                'amount'             => $this->payment->amount,
            ];
        }

        return [
            'type'               => 'payment_received',
            'title'              => '💳 Payment Received',
            'message'            => "Customer paid ETB " . number_format($this->payment->amount, 2) . " for \"{$this->payment->serviceRequest->title}\". Complete the job and the payment will be released.",
            'action_url'         => route('payments.provider-history'),
            'payment_id'         => $this->payment->id,
            'service_request_id' => $this->payment->service_request_id,
            'amount'             => $this->payment->amount,
        ];
    }
}
