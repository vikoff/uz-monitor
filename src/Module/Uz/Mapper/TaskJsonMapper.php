<?php

namespace App\Module\Uz\Mapper;

use App\Module\Uz\Model\Task;
use App\Module\Uz\Model\TaskInterface;

class TaskJsonMapper implements TaskJsonMapperInterface
{
    public function fromJson(string $taskJson): TaskInterface
    {
        $data = json_decode($taskJson, true);
        return new Task(
            $data['stationCodeFrom'],
            $data['stationCodeTo'],
            $data['date'],
            $data['notifyChannels'],
            $data['nextAt'],
            $data['testMode'],
            $data['checkInterval'],
            $data['id'],
            $data['createdAt']
        );
    }

    public function toJson(TaskInterface $task): string
    {
        return json_encode([
            'stationCodeFrom' => $task->getStationCodeFrom(),
            'stationCodeTo' => $task->getStationCodeTo(),
            'date' => $task->getDate(),
            'notifyChannels' => $task->getNotifyChannels(),
            'nextAt' => $task->getNextAt(),
            'testMode' => $task->isTestMode(),
            'checkInterval' => $task->getCheckInterval(),
            'id' => $task->getId(),
            'createdAt' => $task->getCreatedAt(),
        ]);
    }
}
