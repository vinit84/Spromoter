<?php

namespace App\Console\Commands;

use App\Jobs\TakeStoreScreenshotJob;
use App\Models\Store;
use Illuminate\Console\Command;

class TakeStoreScreenshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:take-store-screenshot {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take store screenshot for preview image';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $syncAll = $this->option('all');

        if ($syncAll){
            $stores = Store::all();
        }else{
            $stores = Store::whereNull('preview_image')->get();
        }

        foreach ($stores as $store){
            $this->info('Taking screenshot for store: ' . $store->name);
            TakeStoreScreenshotJob::dispatchSync($store);
        }

        $this->info('Done');
    }
}
