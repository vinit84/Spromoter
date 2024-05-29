<?php

namespace App\Jobs;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Browsershot\Browsershot;

class TakeStoreScreenshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Store $store)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = storage_path("app/screenshots/{$this->store->id}.png");

        try {
            Browsershot::url($this->store->url)
                ->setNodeBinary(config('app.node_binary'))
                ->setNpmBinary(config('app.npm_binary'))
                ->waitUntilNetworkIdle()
                ->save($path);

            if (\File::exists($path)){
                $this->store->clearMediaCollection('preview_image');

                $media = $this->store->copyMedia($path)->toMediaCollection('preview_image');

                $this->store->update([
                    'preview_image' => route('common.store-preview-image', [$this->store, $media, 'preview_image.png']),
                    'preview_image_updated_at' => now()
                ]);

                unlink($path);
            }
        }catch (\Exception $exception){
            logger($exception->getMessage());
        }
    }
}
