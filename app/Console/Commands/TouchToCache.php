<?php

namespace App\Console\Commands;

use App\Models\BoardingHouseFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TouchToCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:touch-to-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Touch the cache to ensure it is up-to-date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $files = BoardingHouseFile::all(['url']);

        foreach ($files as $file) {
            $this->info("Touching file: {$file->url}");
            Http::get($file->url);
            Http::get(resizeImageCloudinary($file->url, 400, 350));
            Http::get(resizeImageCloudinary($file->url, 1200, 600));
            Http::get(resizeImageCloudinary($file->url, 400, 270));
            Http::get(resizeImageCloudinary($file->url, 300, 200));
            Http::get(resizeImageCloudinary($file->url, 800, 450));
        }
    }
}
