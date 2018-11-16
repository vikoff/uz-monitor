<?php

namespace app\components\UzMonitor\Notifier;

class UnixBaloonNotifier implements NotifierInterface
{
    public function notify($message)
    {
        $cmd = 'notify-send ' . escapeshellarg(mb_substr($message, 0, 255));
        shell_exec($cmd);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'unix-baloon';
    }
}
