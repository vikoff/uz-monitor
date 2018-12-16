<?php

namespace App\Module\Uz\Component;

use app\components\log\LoggerOptionalTrait;
use Psr\Log\LoggerInterface;

class MonitorDaemon
{
    use LoggerOptionalTrait;
    /**
     * @var MonitorComponentInterface
     */
    private $monitorComponent;
    /**
     * @var int
     */
    private $sleep;

    public function __construct(
        MonitorComponentInterface $monitorComponent,
        int $sleep,
        LoggerInterface $logger = null
    ) {
        $this->monitorComponent = $monitorComponent;
        $this->sleep = $sleep;
        $this->setLoggerOptional($logger);
    }

    public function run()
    {
        while (true) {
            try {
                $this->monitorComponent->processAllTasks();
                $this->sleep();
            } catch (\Exception $e) {
                $this->getLogger()->error(
                    'Error while processing: '. $e->getMessage()
                );
                $this->sleep();
            }
        }
    }

    private function sleep(): void
    {
        $this->getLogger()->info("Sleep $this->sleep sec");
        sleep($this->sleep);
    }
}
