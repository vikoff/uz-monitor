<?php

namespace app\components\UzMonitor;

use app\components\log\LoggerOptionalTrait;
use app\components\UzMonitor\Notifier\NotifierInterface;
use Psr\Log\LoggerInterface;

class UzMonitor
{
    use LoggerOptionalTrait;

    /**
     * @var UzApiAdapter
     */
    private $uzApi;
    /**
     * @var int
     */
    private $sleepSeconds;
    /**
     * @var bool
     */
    private $testMode;
    /**
     * @var NotifierInterface[]|array
     */
    private $notifiers;
    /**
     * @var NotifierInterface[]|array
     */
    private $localNotifiers;

    /**
     * @param UzApiAdapter $uzApi
     * @param int $sleepSeconds
     * @param bool $testMode
     * @param NotifierInterface[] $notifiers
     * @param NotifierInterface[] $localNotifiers
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        UzApiAdapter $uzApi,
        $sleepSeconds,
        $testMode,
        array $notifiers,
        array $localNotifiers,
        LoggerInterface $logger = null
    ) {
        foreach ($notifiers as $notifier) {
            if (!$notifier instanceof NotifierInterface) {
                throw new \InvalidArgumentException('expected NotifierInterface');
            }
        }
        foreach ($localNotifiers as $notifier) {
            if (!$notifier instanceof NotifierInterface) {
                throw new \InvalidArgumentException('expected NotifierInterface');
            }
        }

        $this->uzApi = $uzApi;
        $this->sleepSeconds = $sleepSeconds;
        $this->testMode = $testMode;
        $this->notifiers = $notifiers;
        $this->localNotifiers = $localNotifiers;
        $this->setLoggerOptional($logger);
    }

    public function startMonitoring()
    {
        $api = $this->uzApi;
        $testPrefix = $this->testMode ? 'TEST ' : '';

        $params = array_merge(
            $api->dumpParams(),
            [
                'sleep' => $this->sleepSeconds,
                'testMode' => $this->testMode,
            ]
        );

        $this->getLogger()->info($testPrefix . 'Started with params: '. json_encode($params, JSON_UNESCAPED_UNICODE));

        while (true) {
            try {
                $newPlaces = $this->testMode
                    ? $api->parseTest()
                    : $api->parse();

                if (count($newPlaces) > 0) {
                    $this->notifyLocal($testPrefix . 'UZ Parser new places!');
                    if ($this->testMode) {
                        $this->notify(array_merge(['TEST TEST TEST'], $newPlaces), $api->buildViewUrl());
                    } else {
                        $this->notify($newPlaces, $api->buildViewUrl());
                    }
                }
            } catch (\Exception $e) {
                $this->getLogger()->error('caught exception: ' . $e->getMessage());
                $this->notifyLocal('UZ Parser error: ' . $e->getMessage());
            }
            $this->sleep();
        }
    }

    private function sleep()
    {
        $this->getLogger()->info("sleep $this->sleepSeconds\n");
        sleep($this->sleepSeconds);
    }

    /**
     * @param array $newPlaces
     * @param string $viewUrl
     * @throws \Exception
     */
    private function notify(array $newPlaces, $viewUrl)
    {
        if (count($this->notifiers) === 0) {
            return;
        }

        $this->getLogger()->notice('NOTIFY: ' . json_encode($newPlaces, JSON_UNESCAPED_UNICODE));

        $message = implode("\n", $newPlaces) . "\n\n$viewUrl";

        foreach ($this->notifiers as $notifier) {
            try {
                $notifier->notify($message);
            } catch (\Exception $e) {
                $errMsg = 'Unable to send notification: ' . $e->getMessage();
                $this->getLogger()->error($errMsg);
                $this->notifyLocal($errMsg);
            }
        }
    }

    /**
     * @param string $message
     */
    private function notifyLocal($message)
    {
        foreach ($this->localNotifiers as $notifier) {
            try {
                $notifier->notify($message);
            } catch (\Exception $e) {
                $this->getLogger()->error('Unable to send local notification: ' . $e->getMessage());
            }
        }
    }
}
