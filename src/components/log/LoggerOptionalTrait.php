<?php

namespace app\components\log;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait LoggerOptionalTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    protected function setLoggerOptional(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }
}
