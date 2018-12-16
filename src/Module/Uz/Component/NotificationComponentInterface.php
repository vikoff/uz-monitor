<?php

namespace App\Module\Uz\Component;

use App\Module\Uz\Model\NewPlacesInterface;
use App\Module\Uz\Model\TaskInterface;

interface NotificationComponentInterface
{
    public function sendNotification(
        TaskInterface $task,
        NewPlacesInterface $newPlaces
    ): void;
}
