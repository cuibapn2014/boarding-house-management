<?php

namespace App\Console\Commands;

use App\Models\BoardingHouse;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireListingsCommand extends Command
{
    protected $signature = 'listings:expire';

    protected $description = 'Kiểm tra tin đăng hết hạn (expires_at) và tắt hiển thị (is_publish = false). Chạy mỗi phút.';

    public function handle(): int
    {
        $now = Carbon::now();
        $count = BoardingHouse::where('is_publish', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->update(['is_publish' => false]);

        if ($count > 0) {
            $this->info("Đã tắt hiển thị {$count} tin đăng hết hạn.");
        }

        return self::SUCCESS;
    }
}
