<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->botToken = config('telegram.bots.mybot.token');
        $this->chatId = env('TELEGRAM_CHAT_ID');
    }

    public function sendMessage(string $message): bool
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $response = Http::post($url, [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);

        return $response->successful();
    }
}
