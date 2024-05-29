<?php

namespace App\Listeners;

use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Notifications\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Cashier;

class StripeWebhookHandledListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $payload = $event->payload;
        $data = $payload['data']['object'];

        switch ($payload['type']) {
            case 'customer.subscription.created':
                $this->subscriptionCreated($data);
                break;
            case 'customer.subscription.updated':
                $this->subscriptionUpdated($data);
                break;
            case 'customer.subscription.deleted':
                $this->subscriptionDeleted($data);
                break;

            case 'customer.subscription.trial_will_end':
                $this->subscriptionTrialWillEnd($data);
                break;
            case 'customer.subscription.activated':
                $this->subscriptionActivated($data);
                break;

            // Invoice
            case 'invoice.created':
                $this->invoiceCreated($data);
                break;
            case 'invoice.payment_succeeded':
                $this->invoicePaymentSucceeded($data);
                break;
            case 'invoice.payment_failed':
                $this->invoicePaymentFailed($data);
                break;

            // Plan
            case 'product.deleted':
                $this->productDeleted($data);
                break;

            default:
                //Log::error('Stripe Event Not Handled', $payload);
                break;
        }
    }

    public function subscriptionCreated($data)
    {
        $billable = Cashier::findBillable($data['customer']);

        $subscription = Subscription::whereStripeId($data['id'])->first();
        $subscription?->update([
            'store_id' => $data['metadata']['store_id'] ?? null,
            'plan_id' => $data['metadata']['plan_id'] ?? null,
            'total_orders' => $data['metadata']['total_orders'] ?? null,
        ]);

        $billable?->notify(new NewNotification(
            trans('You are successfully subscribed to :plan', ['plan' => $subscription->name]),
            route('user.profile.billing.index'), 'ti ti-crown')
        );
    }

    private function subscriptionUpdated($data){}

    private function subscriptionDeleted(mixed $data){}

    private function subscriptionTrialWillEnd(mixed $data){}

    private function subscriptionActivated(mixed $data){}

    private function invoiceCreated(mixed $data): void
    {
        Invoice::create([
            'subscription' => $data['subscription'],
            'number' => $data['number'],
            'stripe_id' => $data['id'],
            'currency' => $data['currency'],
            'customer' => $data['customer'],
            'hosted_invoice_url' => $data['hosted_invoice_url'],
            'invoice_pdf' => $data['invoice_pdf'],
            'amount' => $data['amount_paid'],
            'status' => $data['status'],
            'data' => $data,
        ]);
    }

    private function invoicePaymentSucceeded(mixed $data): void
    {
        $invoice = Invoice::whereStripeId($data['id'])->first();

        if ($invoice) {
            $invoice->update([
                'status' => $data['status'],
                'data' => $data,
            ]);

            $subscription = Subscription::whereStripeId($invoice->subscription)->first();

            $subscription?->user->notify(new NewNotification(
                message: 'Your payment was successful.',
                url: route('user.invoices.show', $subscription),
                icon: 'ti ti-shopping-cart-check',
                isMail: true,
            ));
        }
    }

    private function invoicePaymentFailed(mixed $data): void
    {
        $invoice = Invoice::whereStripeId($data['id'])->first();

        if ($invoice) {
            $invoice->update([
                'status' => $data['status'],
                'data' => $data,
            ]);

            $subscription = Subscription::whereStripeId($invoice->subscription)->first();

            $subscription?->user->notify(new NewNotification(
                message: 'Your payment failed.',
                url: route('user.invoices.show', $subscription),
                icon: 'ti ti-close',
                isMail: true,
            ));
        }
    }

    private function productDeleted(mixed $data): void
    {
        $product = Plan::whereStripeId($data['id'])->first();

        $product?->delete();
    }
}
