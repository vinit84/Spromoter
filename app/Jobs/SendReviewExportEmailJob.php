<?php

namespace App\Jobs;

use App\Mail\SendReviewExportEmailMail;
use App\Models\ReviewExport;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReviewExportEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected ReviewExport $export, protected string $email)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->export->update([
            'status' => ReviewExport::STATUS_COMPLETED,
        ]);

        $this->export->store->user->notify(new NewNotification(
            message: 'Your review export is ready to download.',
            url: route('user.reviews.export.download', $this->export),
            icon: 'ti ti-download'
        ));

        Mail::to($this->email)->send(new SendReviewExportEmailMail($this->export));
    }

    public function fail(\Throwable $exception = null): void
    {
        $this->export->update([
            'status' => ReviewExport::STATUS_FAILED,
            'failure_reason' => $exception?->getMessage(),
        ]);

        $this->export->store->user->notify(new NewNotification(
            message: 'Your review export failed to generate.',
            url: route('user.reviews.export.index'),
            icon: 'ti ti-download',
            type: 'danger'
        ));
    }
}
