<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordLogService
{
    public function notify(string $message, array $context = [], ?string $monologLevel = null): void
    {
        $url = config('discord.webhook_url', '');
        if ($url === '') {
            return;
        }

        $text = $message;
        if ($context !== []) {
            $encoded = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            if ($encoded !== false) {
                $text .= "\n```\n".substr($encoded, 0, 1500)."\n```";
            }
        }
        if ($monologLevel !== null) {
            $text = "**[{$monologLevel}]** ".$text;
        }
        $text = substr($text, 0, 1990);

        try {
            Http::timeout(5)->asJson()->post($url, [
                'username' => config('discord.username'),
                'content' => $text,
            ]);
        } catch (\Throwable $e) {
            Log::debug('Discord webhook failed: '.$e->getMessage());
        }
    }
}
