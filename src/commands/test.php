<?php

use app\components\UzMonitor\Notifier\TelegramNotifier;
use app\components\UzMonitor\Notifier\UnixBaloonNotifier;
use app\components\UzMonitor\UzApiAdapter;
use app\components\UzMonitor\UzMonitor;
use app\models\Stations;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../configs/'));
$loader->load('container.yml');
