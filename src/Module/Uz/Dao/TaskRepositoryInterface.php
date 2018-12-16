<?php

namespace App\Module\Uz\Dao;

use App\Module\Uz\Model\TaskInterface;

interface TaskRepositoryInterface
{
    /**
     * @return TaskInterface[]
     */
    public function getForProcessing(): array;

    public function update(TaskInterface $task);
}
