<?php

namespace App\Helpers\Logging;

use Monolog\Formatter\LogstashFormatter;

class JsonLogger
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $formatter = new LogstashFormatter(env('APP_NAME'));
            $handler->setFormatter($formatter);
        }
    }
}
