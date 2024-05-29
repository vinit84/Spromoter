<?php

namespace App\Console\Commands;

use App\Mail\ReviewRequestEmail;
use App\Models\OrderEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScheduledReviewRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-scheduled-review-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $scheduledEmails = OrderEmail::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->with('order')
            ->get();

        foreach ($scheduledEmails as $scheduledEmail) {
            $order = $scheduledEmail->order;
            $subject = $scheduledEmail->store->setting('emails.review_request_email_subject');
            $subject = str($subject)
                ->replace('{store.name}', $scheduledEmail->store->name);

            $body = $scheduledEmail->store->setting('emails.review_request_email_body');
            $body = str($body)
                ->replace('{customer.name}', $order->customer_name)
                ->replace('{store.name}', $scheduledEmail->store->name)
                ->replace('{product.name}', $scheduledEmail->item->name);

            try {
                Mail::to($order->customer_email)->send(new ReviewRequestEmail($subject, $body));

                $scheduledEmail->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }catch (\Exception $exception) {
                $scheduledEmail->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'failed_reason' => $exception->getMessage(),
                ]);
            }
        }
    }
}
