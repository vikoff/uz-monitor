<?php

namespace App\Module\Uz\Factory;

use App\Module\Uz\Model\TaskInterface;
use App\Module\Uz\Model\UzRequest;
use App\Module\Uz\Model\UzRequestInterface;

class UzRequestFactory implements UzRequestFactoryInterface
{
    /**
     * @param TaskInterface $task
     * @return UzRequestInterface
     * @throws \Exception
     */
    public function buildFromTask(TaskInterface $task): UzRequestInterface
    {
        return new UzRequest(
            $task->getStationCodeFrom(),
            $task->getStationCodeTo(),
            $task->getDate()
        );
    }
}
