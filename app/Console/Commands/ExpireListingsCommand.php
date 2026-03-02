<?php

namespace App\Console\Commands;

use App\Models\BoardingHouse;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireListingsCommand extends Command
{
    protected $signature = 'listings:expire';

    protected $description = 'Hết hạn đẩy top (expires_at): chỉ xóa trạng thái pin, giữ is_publish.';

    public function handle(): int
    {
        $now = Carbon::now();
        $count = BoardingHouse::whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->update([
                'expires_at' => null,
                'listing_days' => null,
            ]);

        if ($count > 0) {
            $this->info("Đã xóa trạng thái đẩy top hết hạn cho {$count} tin.");
        }

        return self::SUCCESS;
    }
}
