<?php

namespace App\Module\Uz\Dao;

use app\Lib\Util\FileSystemInterface;
use App\Module\Uz\Mapper\TaskJsonMapperInterface;
use App\Module\Uz\Model\TaskInterface;

class TaskFsRepository implements TaskRepositoryInterface
{
    /**
     * @var FileSystemInterface
     */
    private $fileSystem;
    /**
     * @var TaskJsonMapperInterface
     */
    private $taskMapper;

    public function __construct(
        FileSystemInterface $fileSystem,
        TaskJsonMapperInterface $taskMapper
    ) {
        $this->fileSystem = $fileSystem;
        $this->taskMapper = $taskMapper;
    }

    public function getForProcessing(): array
    {
        $tasksForProcessing = [];
        $now = time();
        foreach (glob($this->getDbTableDir() . "/*.json") as $file) {
            $task = $this->taskMapper->fromJson($this->fileSystem->readFile($file));
            if ($task->getNextAt() > $now) {
                $tasksForProcessing[] = $task;
            }
        }

        return $tasksForProcessing;
    }

    public function update(TaskInterface $task)
    {
        if ($task->getId() === null) {
            throw new \LogicException('Unable to update new task');
        }

        foreach (glob($this->getDbTableDir() . "/*.json") as $file) {
            $id = explode($file, '.', 2)[0];
            if (is_numeric($id) && (int)$id === $task->getId()) {
                $this->fileSystem->writeFile($file, $this->taskMapper->toJson($task));
                return $task;
            }
        }

        throw new \RuntimeException('Unable to find task ' . $task->getId());
    }

    /**
     * @return string
     */
    private function getDbTableDir(): string
    {
        return $this->fileSystem->getDbDir() . '/tasks';
    }
}
