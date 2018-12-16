<?php

namespace App\Module\Uz\Component;

use App\Module\Uz\Model\NewPlacesInterface;
use App\Module\Uz\Model\TaskInterface;
use App\Module\Uz\Model\UzResponseInterface;

interface NewPlacesComponentInterface
{
    public function checkPlaces(
        TaskInterface $task,
        UzResponseInterface $uzResponse,
        UzResponseInterface $prevUzResponse
    ): ?NewPlacesInterface;
}
