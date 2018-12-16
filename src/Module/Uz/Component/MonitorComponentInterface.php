<?php

namespace App\Module\Uz\Component;

use App\Module\Uz\Model\TaskInterface;

interface MonitorComponentInterface
{
    public function processAllTasks(): void;

    public function processTask(TaskInterface $task): void;
}
