<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $payment = $this->payment;
        $amount  = number_format($payment->amount, 2);

        if ($payment->isReleased()) {
            return (new MailMessage)
                ->subject("💸 Payment Released — ETB {$amount} — ServiLoc")
                ->greeting("Hello {$notifiable->name},")
                ->line("Your payment has been released!")
                ->line("---")
                ->line("**Service:** {$payment->serviceRequest->title}")
                ->line("**Amount Released:** ETB {$amount}")
                ->line("**Transaction ID:** {$payment->transaction_id}")
                ->line("---")
                ->action('View Earnings', route('payments.provider-history'))
                ->line("Thank you for completing this service!")
                ->salutation("— The ServiLoc Team");
        }

        return (new MailMessage)
            ->subject("💳 Payment Received — ETB {$amount} — ServiLoc")
            ->greeting("Hello {$notifiable->name},")
            ->line("A customer has paid for your service. The amount is held securely until you complete the job.")
            ->line("---")
            ->line("**Service:** {$payment->serviceRequest->title}")
            ->line("**Amount Held:** ETB {$amount}")
            ->line("**Customer:** {$payment->customer->name}")
            ->line("**Transaction ID:** {$payment->transaction_id}")
            ->line("---")
            ->action('View Job Details', route('payments.receipt', $payment))
            ->line("Complete the service and the customer will release the payment to you.")
            ->salutation("— The ServiLoc Team");
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
