<?php

namespace App\Module\Uz\Factory;

use App\Module\Uz\Model\TaskInterface;
use App\Module\Uz\Model\UzRequestInterface;

interface UzRequestFactoryInterface
{
    public function buildFromTask(TaskInterface $task): UzRequestInterface;
}
