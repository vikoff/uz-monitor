<?php

namespace App\Module\Uz\Component\Notifier;

interface NotifierInterface
{
    /**
     * @param string $message
     * @param array $params
     * @return void
     */
    public function notify(string $message, array $params = []): void;

    /**
     * @return string
     */
    public function getName(): string;
}
