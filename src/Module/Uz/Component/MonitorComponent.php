<?php


namespace App\Module\Uz\Component;

use app\components\log\LoggerOptionalTrait;
use App\Module\Uz\Dao\TaskRepositoryInterface;
use App\Module\Uz\Dao\UzResponseRepositoryInterface;
use App\Module\Uz\Factory\UzRequestFactoryInterface;
use App\Module\Uz\Model\TaskInterface;
use Psr\Log\LoggerInterface;

class MonitorComponent implements MonitorComponentInterface
{
    use LoggerOptionalTrait;
    /**
     * @var NewPlacesComponentInterface
     */
    private $newPlacesComponent;
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;
    /**
     * @var UzApiInterface
     */
    private $uzApi;
    /**
     * @var UzRequestFactoryInterface
     */
    private $uzRequestFactory;
    /**
     * @var UzResponseRepositoryInterface
     */
    private $uzResponseRepository;
    /**
     * @var NotificationComponentInterface
     */
    private $notificationComponent;
    /**
     * @var int
     */
    private $defaultCheckInterval;

    public function __construct(
        NewPlacesComponentInterface $newPlacesComponent,
        NotificationComponentInterface $notificationComponent,
        UzRequestFactoryInterface $uzRequestFactory,
        TaskRepositoryInterface $taskRepository,
        UzResponseRepositoryInterface $uzResponseRepository,
        UzApiInterface $uzApi,
        int $defaultCheckInterval,
        LoggerInterface $logger = null
    ) {
        $this->setLoggerOptional($logger);
        $this->newPlacesComponent = $newPlacesComponent;
        $this->uzRequestFactory = $uzRequestFactory;
        $this->taskRepository = $taskRepository;
        $this->uzApi = $uzApi;
        $this->uzResponseRepository = $uzResponseRepository;
        $this->notificationComponent = $notificationComponent;
        $this->defaultCheckInterval = $defaultCheckInterval;
    }

    public function processAllTasks(): void
    {
        foreach ($this->taskRepository->getForProcessing() as $task) {
            $this->processTask($task);
        }
    }

    public function processTask(TaskInterface $task): void
    {
        $uzResponse = $this->uzApi->getTrains(
            $this->uzRequestFactory->buildFromTask($task)
        );

        $prevUzResponse = $this->uzResponseRepository->getPreviousResponse($task);
        $uzResponse = $this->uzResponseRepository->store($task, $uzResponse);
        $this->taskRepository->update(
            $task->withNextAt(
                $task->getCheckInterval() !== null
                    ? $task->getCheckInterval()
                    : $this->defaultCheckInterval
            )
        );

        if ($prevUzResponse !== null) {
            $newPlaces = $this->newPlacesComponent->checkPlaces(
                $task,
                $uzResponse,
                $prevUzResponse
            );
            if ($newPlaces !== null) {
                $this->notificationComponent->sendNotification($task, $newPlaces);
            }
        }
    }
}
