<?php

namespace App\Imports;

use App\Models\ReviewImport;
use App\Models\Store;
use App\Notifications\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Throwable;

class Importer implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithEvents, WithValidation, SkipsEmptyRows, SkipsOnError, ShouldQueue
{

    use RegistersEventListeners;

    public function __construct(protected Store $store, protected ReviewImport $reviewImport)
    {
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function model(array $row)
    {
    }

    public function rules(): array
    {
        return [];
    }


    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $this->store->user->notify(new NewNotification(
                    message: trans('Your reviews import has failed. Please check the file and try again.'),
                    url: route('user.reviews.moderation.index'),
                    icon: 'ti ti-alert-triangle',
                    type: 'error',
                    isMail: true
                ));

                $this->reviewImport->update([
                    'status' => ReviewImport::STATUS_FAILED,
                    'to' => now(),
                    'failed_at' => now(),
                    'failure_reason' => $event->getException()->getMessage(),
                ]);
            },
            AfterImport::class => function (AfterImport $event) {
                $this->store->user->notify(new NewNotification(
                    message: trans('Your reviews have been imported successfully'),
                    url: route('user.reviews.moderation.index'),
                    icon: 'ti ti-file-import',
                    isMail: true
                ));

                $this->reviewImport->update([
                    'status' => ReviewImport::STATUS_COMPLETED,
                    'to' => now(),
                    'completed_at' => now(),
                ]);
            }
        ];
    }

    public function onError(Throwable $e)
    {
        // TODO: Implement onError() method.
    }
}
