<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemUpgradedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:system-upgraded-command {ver} {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version = $this->argument('ver');
        $url = $this->argument('url');

        $admins = \App\Models\User::whereGroup('admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewNotification(
                message: 'System has been upgraded to version ' . $version,
                url: $url,
                icon: 'ti ti-download',));
        }
    }
}
