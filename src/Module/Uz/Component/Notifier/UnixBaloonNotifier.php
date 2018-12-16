<?php

namespace App\Module\Uz\Component\Notifier;

class UnixBaloonNotifier implements NotifierInterface
{
    public function notify(string $message, array $params = []): void
    {
        $cmd = 'notify-send ' . escapeshellarg(mb_substr($message, 0, 255));
        shell_exec($cmd);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'unix-baloon';
    }
}