<?php

namespace app\components\log;

use Psr\Log\AbstractLogger;

class CliLogger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        echo date('m.d H:i:s') . " [$level] $message\n";
    }
}
