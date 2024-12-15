<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database vào file SQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $database   = config('database.connections.mysql.database');
        $username   = config('database.connections.mysql.username');
        $password   = config('database.connections.mysql.password');
        $host       = config('database.connections.mysql.host');
        $backupPath = storage_path('backups');
        $fileName   = Str::slug(config('app.name'))  . '_' . date('Y-m-d_H-i-s') . '_backup.sql';

         // Tạo thư mục nếu chưa tồn tại
         if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filePath = $backupPath . '/' . $fileName;

        // Command mysqldump
        $command = "mysqldump -h {$host} -u {$username} --password={$password} {$database} > {$filePath}";

        // Chạy command
        $process = Process::fromShellCommandline($command);
        $process->run();

        if ($process->isSuccessful()) {
            $this->info("Database đã được backup thành công: {$filePath}");
            return Command::SUCCESS;
        } else {
            $this->error("Lỗi khi backup database: " . $process->getErrorOutput());
            return Command::FAILURE;
        }
    }
}
