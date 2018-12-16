<?php

namespace App\Module\Uz\Dao;

use app\Lib\Util\FileSystemInterface;
use App\Module\Uz\Mapper\UzResponseJsonMapperInterface;
use App\Module\Uz\Model\TaskInterface;
use App\Module\Uz\Model\UzResponseInterface;

class UzResponseFsRepository implements UzResponseRepositoryInterface
{
    /**
     * @var FileSystemInterface
     */
    private $fileSystem;
    /**
     * @var UzResponseJsonMapperInterface
     */
    private $uzResponseMapper;

    public function __construct(
        FileSystemInterface $fileSystem,
        UzResponseJsonMapperInterface $uzResponseMapper
    ) {
        $this->fileSystem = $fileSystem;
        $this->uzResponseMapper = $uzResponseMapper;
    }

    public function getPreviousResponse(TaskInterface $task): ?UzResponseInterface
    {
        $fullPath = $this->getDbTableDir() . $this->buildFileName($task);
        if (file_exists($fullPath)) {
            return $this->uzResponseMapper->fromJson(
                $this->fileSystem->readFile($fullPath)
            );
        } else {
            return null;
        }
    }

    /**
     * @param TaskInterface $task
     * @param UzResponseInterface $uzResponse
     * @return UzResponseInterface
     */
    public function store(
        TaskInterface $task,
        UzResponseInterface $uzResponse
    ): UzResponseInterface {
        $this->fileSystem->writeFile(
            $this->getDbTableDir() . $this->buildFileName($task),
            $this->uzResponseMapper->toJson($uzResponse)
        );

        return $uzResponse;
    }

    /**
     * @return string
     */
    private function getDbTableDir(): string
    {
        return $this->fileSystem->getDbDir() . '/uz-response';
    }

    /**
     * @param TaskInterface $task
     * @return string
     */
    private function buildFileName(TaskInterface $task): string
    {
        return $task->getStationCodeFrom()
            . '-' . $task->getStationCodeTo()
            . '-' . $task->getDate();
    }
}
