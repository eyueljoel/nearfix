<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Notifications\PaymentReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Show the payment confirmation page.
     * Customer lands here after accepting an offer.
     */
    public function show(Offer $offer)
    {
        $user = Auth::user();

        // Only the customer who owns the request can pay
        $serviceRequest = $offer->serviceRequest;
        abort_if($serviceRequest->user_id !== $user->id, 403);
        abort_if($offer->status !== 'accepted', 404, 'This offer is not available for payment.');

        // Already paid?
        $existingPayment = Payment::where('offer_id', $offer->id)->first();
        if ($existingPayment) {
            return redirect()->route('payments.receipt', $existingPayment)
                ->with('info', 'This offer has already been paid.');
        }

        return view('payments.checkout', compact('offer', 'serviceRequest'));
    }

    /**
     * Process the payment (simulated).
     */
    public function pay(Request $request, Offer $offer)
    {
        $user = Auth::user();
        $serviceRequest = $offer->serviceRequest;

        abort_if($serviceRequest->user_id !== $user->id, 403);
        abort_if($offer->status !== 'accepted', 404);

        // Prevent duplicate payment
        if (Payment::where('offer_id', $offer->id)->exists()) {
            return redirect()->route('customer.requests')
                ->with('error', 'Payment already processed for this offer.');
        }

        $request->validate([
            'payment_method' => 'required|in:simulated,telebirr,cbe_birr,bank_transfer',
        ]);

        DB::transaction(function () use ($offer, $serviceRequest, $user, $request) {
            // Create payment record
            $payment = Payment::create([
                'service_request_id' => $serviceRequest->id,
                'offer_id'           => $offer->id,
                'customer_id'        => $user->id,
                'provider_id'        => $offer->provider_id,
                'amount'             => $offer->price,
                'currency'           => 'ETB',
                'status'             => 'paid',
                'transaction_id'     => Payment::generateTransactionId(),
                'payment_method'     => $request->payment_method,
                'paid_at'            => now(),
            ]);

            // Move request to in_progress
            $serviceRequest->update(['status' => 'in_progress']);

            // Notify provider
            $offer->load('provider', 'serviceRequest');
            $offer->provider->notify(new PaymentReceived($payment));
        });

        return redirect()->route('payments.receipt', Payment::where('offer_id', $offer->id)->first())
            ->with('success', 'Payment successful! The provider has been notified.');
    }

    /**
     * Show the payment receipt.
     */
    public function receipt(Payment $payment)
    {
        $user = Auth::user();

        // Only customer or provider of this payment can view the receipt
        abort_if(
            $payment->customer_id !== $user->id && $payment->provider_id !== $user->id,
            403
        );

        $payment->load('serviceRequest', 'offer', 'customer', 'provider');

        return view('payments.receipt', compact('payment'));
    }

    /**
     * Customer marks service as complete → releases payment to provider.
     */
    public function release(Payment $payment)
    {
        $user = Auth::user();

        abort_if($payment->customer_id !== $user->id, 403);
        abort_if(!$payment->isPaid(), 400, 'Payment cannot be released in its current state.');

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status'       => 'released',
                'released_at'  => now(),
            ]);

            // Move request to completed
            $payment->serviceRequest->update(['status' => 'completed']);

            // Notify provider
            $payment->load('provider', 'serviceRequest');
            $payment->provider->notify(new \App\Notifications\PaymentReceived($payment));
        });

        return redirect()->route('customer.requests')
            ->with('success', 'Service marked as complete. Payment released to provider!');
    }

    /**
     * Payment history for customer.
     */
    public function customerHistory(Request $request)
    {
        $payments = Payment::forCustomer(Auth::id())
            ->with(['serviceRequest', 'provider', 'offer'])
            ->latest()
            ->paginate(15);

        return view('payments.customer-history', compact('payments'));
    }

    /**
     * Payment history for provider (earnings).
     */
    public function providerHistory(Request $request)
    {
        $payments = Payment::forProvider(Auth::id())
            ->with(['serviceRequest', 'customer', 'offer'])
            ->latest()
            ->paginate(15);

        $totalEarned   = Payment::forProvider(Auth::id())->where('status', 'released')->sum('amount');
        $pendingAmount = Payment::forProvider(Auth::id())->where('status', 'paid')->sum('amount');

        return view('payments.provider-history', compact('payments', 'totalEarned', 'pendingAmount'));
    }

    /**
     * Admin: All payments overview.
     */
    public function adminOverview(Request $request)
    {
        $payments = Payment::with(['serviceRequest', 'customer', 'provider', 'offer'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_paid'     => Payment::where('status', 'paid')->sum('amount'),
            'total_released' => Payment::where('status', 'released')->sum('amount'),
            'total_refunded' => Payment::where('status', 'refunded')->sum('amount'),
            'total_count'    => Payment::count(),
        ];

        return view('payments.admin-overview', compact('payments', 'stats'));
    }
}
