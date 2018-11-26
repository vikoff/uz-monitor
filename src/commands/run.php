<?php

use app\components\UzMonitor\Notifier\TelegramNotifier;
use app\components\UzMonitor\Notifier\UnixBaloonNotifier;
use app\components\UzMonitor\UzApiAdapter;
use app\components\UzMonitor\UzMonitor;
use app\models\Stations;

require __DIR__ . '/../../vendor/autoload.php';

$testRun = true;

$logger = new \app\components\log\CliLogger();

$botId = '';
$botSecret = '';

//$chatId = '-310065954'; // Booka 2.0
$chatId = '311856881'; // yuriy.novikov

$telegramNotifier = new TelegramNotifier($botId, $botSecret, $chatId);
$uzApi = new UzApiAdapter(
    Stations::KIEV,
    Stations::FRANKOVSK,
    '2018-12-20',
    ['043К'],
    '00:00',
    $logger
);

$monitor = new UzMonitor(
    $uzApi,
    60,
    $testRun,
    [$telegramNotifier],
    [new UnixBaloonNotifier()],
    $logger
);

$monitor->startMonitoring();
