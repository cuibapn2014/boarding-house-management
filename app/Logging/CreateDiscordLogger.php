<?php

namespace App\Logging;

use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

class CreateDiscordLogger
{
    public function __invoke(array $config): LoggerInterface
    {
        $level = MonologLogger::toMonologLevel($config['level'] ?? 'error');
        $url = (string) ($config['handler_with']['webhook_url'] ?? config('discord.webhook_url', ''));

        return new MonologLogger('discord', [new DiscordWebhookHandler($url, $level)]);
    }
}
