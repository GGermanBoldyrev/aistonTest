<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTemporaryAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachments:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes temporary attachments older than 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::disk('local')->files('tmp');
        $cutoff = now()->subDay()->getTimestamp();
        $deletedCount = 0;

        foreach ($files as $file) {
            if (Storage::disk('local')->lastModified($file) < $cutoff) {
                Storage::disk('local')->delete($file);
                $deletedCount++;
            }
        }

        $this->info("Successfully deleted {$deletedCount} old temporary attachments.");
        return self::SUCCESS;
    }
}
