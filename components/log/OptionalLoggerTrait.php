<?php

namespace app\components\log;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait OptionalLoggerTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    public function setLoggerOptional(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }
}
