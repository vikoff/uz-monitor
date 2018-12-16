<?php

namespace App\Module\Uz\Mapper;

use App\Module\Uz\Model\TaskInterface;

interface TaskJsonMapperInterface
{
    public function fromJson(string $taskJson): TaskInterface;

    public function toJson(TaskInterface $task): string;
}
