<?php

namespace App\Module\Uz\Dao;

use App\Module\Uz\Model\TaskInterface;
use App\Module\Uz\Model\UzResponseInterface;

interface UzResponseRepositoryInterface
{
    public function getPreviousResponse(TaskInterface $task): ?UzResponseInterface;

    public function store(
        TaskInterface $task,
        UzResponseInterface $uzResponse
    ): UzResponseInterface;
}
