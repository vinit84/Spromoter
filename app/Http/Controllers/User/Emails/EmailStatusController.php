<?php

namespace App\Http\Controllers\User\Emails;

use App\DataTables\User\Emails\EmailStatusDataTable;
use App\Http\Controllers\Controller;
use App\Mail\ReviewRequestEmail;
use App\Models\OrderEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailStatusController extends Controller
{
    public function index(EmailStatusDataTable $dataTable)
    {
        return $dataTable->render('user.emails.email-status.index');
    }

    public function send(OrderEmail $email)
    {
        $body = getStoreSetting('emails.review_request_email_body');
        $subject = getStoreSetting('emails.review_request_email_subject');

        $body = str($body)
            ->replace('{customer.name}', $email->order->customer_name)
            ->replace('{store.name}', $email->order->store->name)
            ->replace('{product.name}', $email->item->product->name);

        $subject = str($subject)
            ->replace('{customer.name}', $email->order->customer_name)
            ->replace('{store.name}', $email->order->store->name)
            ->replace('{product.name}', $email->item->product->name);

        // Check if the user has enough orders to send the email
        $subscription = $email->store->user->subscription();
        if (!$subscription){
            return error(trans('You need to subscribe to a plan to send emails'));
        }

        // Check limit
        $totalOrders = auth()->user()->subscriptions()
            ->whereStoreId($email->store_id)
            ->where(function ($query) {
                $query->where('stripe_status', '=', 'active')
                    ->orWhere('stripe_status', '=', 'trialing');
            })->sum('total_orders');

        $usedOrders = auth()->user()->subscriptions()
            ->whereStoreId($email->store_id)
            ->where(function ($query) {
                $query->where('stripe_status', '=', 'active')
                    ->orWhere('stripe_status', '=', 'trialing');
            })->sum('used_orders');

        if ($totalOrders == $usedOrders){
            $email->update([
                'status' => 'limit_exceeded',
                'limit_exceeded_at' => now(),
                'sent_at' => null,
                'scheduled_at' => null,
                'failed_at' => null,
                'failed_reason' => null,
            ]);

            return error(trans('You have reached the limit of emails you can send'));
        }

        try {
            Mail::to($email->order->customer_email)
                ->send(new ReviewRequestEmail(
                        mailSubject: $subject,
                        with: [
                            'body' => $body,
                            'layout' => [
                                'store' => activeStore()->name
                            ],
                            'image' => $email->item->product->image,
                            'storeId' => $email->store->uuid,
                            'emailId' => $email->uuid,
                            'product' => $email->item->product->name,
                            'productId' => $email->item->product->uuid,
                            'description' => str($email->item->product->description)->limit(),
                            'purchaseDate' => dateFormat($email->order->created_at),
                        ]
                    )
                );

            $email->store->user->subscription()->increment('used_orders');

            $email->update([
                'status' => 'sent',
                'sent_at' => now(),
                'scheduled_at' => null,
                'failed_at' => null,
                'failed_reason' => null,
                'limit_exceeded_at' => null,
            ]);
        }catch (\Throwable $e){
            $email->update([
                'status' => 'failed',
                'failed_at' => now(),
                'failed_reason' => $e->getMessage(),
                'sent_at' => null,
                'scheduled_at' => null,
                'limit_exceeded_at' => null,
            ]);
        }
        return success(trans('Email is sent successfully'));
    }

    public function destroy(OrderEmail $email)
    {
        if ($email->sent_at != null){
            return error(trans('You can\'t delete a sent email'));
        }

        $email->delete();

        return success(trans('Email is deleted successfully'));
    }
}
