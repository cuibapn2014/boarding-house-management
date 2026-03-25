<?php

namespace App\Logging;

use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DiscordWebhookHandler extends AbstractProcessingHandler
{
    public function __construct(
        protected string $webhookUrl,
        int|string|\Monolog\Level $level = \Monolog\Level::Debug,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        if ($this->webhookUrl === '') {
            return;
        }

        $text = sprintf('**[%s]** %s', $record->level->getName(), $record->message);
        if ($record->context !== []) {
            $encoded = json_encode($record->context, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            if ($encoded !== false) {
                $text .= "\n```\n".substr($encoded, 0, 1500)."\n```";
            }
        }
        $text = substr($text, 0, 1990);

        try {
            Http::timeout(5)->asJson()->post($this->webhookUrl, [
                'username' => config('discord.username'),
                'content' => $text,
            ]);
        } catch (\Throwable) {
            // Tránh vòng lỗi khi Discord không phản hồi
        }
    }
}
