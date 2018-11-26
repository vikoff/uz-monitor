<?php

namespace app\components\UzMonitor\Notifier;

interface NotifierInterface
{
    /**
     * @param string $message
     * @return void
     */
    public function notify($message);

    /**
     * @return string
     */
    public function getName();
}
