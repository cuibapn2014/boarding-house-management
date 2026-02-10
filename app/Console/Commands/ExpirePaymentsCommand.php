<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpirePaymentsCommand extends Command
{
    protected $signature = 'payments:expire';

    protected $description = 'Kiểm tra các thanh toán hết hạn (expires_at) và cập nhật trạng thái sang expired. Chạy mỗi phút.';

    public function handle(): int
    {
        $now = Carbon::now();
        $count = Payment::where('status', Payment::STATUS_PENDING)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->update(['status' => Payment::STATUS_EXPIRED]);

        if ($count > 0) {
            $this->info("Đã cập nhật {$count} thanh toán sang trạng thái hết hạn.");
        }

        return self::SUCCESS;
    }
}
