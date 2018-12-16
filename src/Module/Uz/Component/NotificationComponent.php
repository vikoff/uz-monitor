<?php

namespace App\Module\Uz\Component;

use app\components\log\LoggerOptionalTrait;
use App\Module\Uz\Component\Notifier\NotifierInterface;
use App\Module\Uz\Model\NewPlacesInterface;
use App\Module\Uz\Model\TaskInterface;
use Psr\Log\LoggerInterface;

class NotificationComponent implements NotificationComponentInterface
{
    use LoggerOptionalTrait;

    /**
     * @var NotifierInterface[]
     */
    private $indexedNotifiers;

    /**
     * @param NotifierInterface[] $notifiers
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $notifiers, LoggerInterface $logger = null)
    {
        $indexedNotifiers = [];
        foreach ($notifiers as $notifier) {
            if (!$notifier instanceof NotifierInterface) {
                throw new \InvalidArgumentException('Not a notifier');
            }
            if (isset($indexedNotifiers[$notifier->getName()])) {
                throw new \InvalidArgumentException(
                    'Duplicated notifier ' . $notifier->getName())
                ;
            }
            $indexedNotifiers[$notifier->getName()] = $notifier;
        }
        $this->indexedNotifiers = $indexedNotifiers;
        $this->setLoggerOptional($logger);
    }

    public function sendNotification(
        TaskInterface $task,
        NewPlacesInterface $newPlaces
    ): void {
        $message = $this->buildMessage($newPlaces);

        foreach ($task->getNotifyChannels() as $key => $val) {
            [$name, $params] = $this->buildNotifyChannelInfo($key, $val);

            if (!isset($this->indexedNotifiers[$name])) {
                $this->getLogger()->error('Invalid notifier ' . $name);
                continue;
            }
            try {
                $this->indexedNotifiers[$name]->notify($message, $params);
            } catch (\Exception $e) {
                $this->getLogger()->error(
                    "Error while sending $name notify: " . $e->getMessage()
                );
            }
        }
    }

    private function buildNotifyChannelInfo($key, $val)
    {
        if (is_numeric($key)) {
            if (!is_string($val)) {
                throw new \InvalidArgumentException('Invalid val, expected string');
            }
            return [$val, []];
        } else {
            if (!is_array($val)) {
                throw new \InvalidArgumentException('Invalid val, expected array');
            }
            return [$key, $val];
        }
    }

    private function buildMessage(
        NewPlacesInterface $newPlaces
    ): string
    {
        $msgArr = [];
        if ($newPlaces->isTestMode()) {
            $msgArr[] = 'TEST (fake data)';
        }

        foreach ($newPlaces->getEntities() as $entity) {
            $msgArr[] = "new places on {$entity->getTrain()} [{$entity->getPlaceType()}]: "
                . "{$entity->getPrevCount()} -> {$entity->getCount()}";
        }

        return implode("\n", $msgArr);
    }
}
