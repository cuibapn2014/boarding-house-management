<?php

namespace App\Console\Commands;

use App\Models\BoardingHouse;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireListingsCommand extends Command
{
    protected $signature = 'listings:expire';

    protected $description = 'Hết hạn đẩy top (expires_at): xóa pushed_at, listing_days, expires_at; giữ is_publish.';

    public function handle(): int
    {
        $now = Carbon::now();
        $count = BoardingHouse::whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->update([
                'pushed_at' => null,
                'expires_at' => null,
                'listing_days' => null,
            ]);

        if ($count > 0) {
            $this->info("Đã xóa trạng thái đẩy top hết hạn cho {$count} tin.");
            discord_log('listings:expire — đã gỡ đẩy top hết hạn', ['count' => $count], 'INFO');
        }

        return self::SUCCESS;
    }
}
