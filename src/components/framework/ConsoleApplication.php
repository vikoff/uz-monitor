<?php

namespace app\components\framework;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ConsoleApplication extends Application
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = $this->setupDI();

        parent::__construct(
            $this->container->getParameter('app.name'),
            $this->container->getParameter('app.version')
        );

        $this->initCommands();
    }

    /**
     * @throws \Exception
     */
    private function initCommands()
    {
        $this->add(new \app\commands\TestCommand());
        $this->addCommands(
            $this->getContainer()->get('commands.broker')->getCommands()
        );
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return ContainerBuilder
     * @throws \Exception
     */
    private function setupDI()
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../../configs/')
        );
        $loader->load('services.yml');
        $loader->load('options.yml');
        $loader->load('options.custom.yml');

        return $container;
    }
}
