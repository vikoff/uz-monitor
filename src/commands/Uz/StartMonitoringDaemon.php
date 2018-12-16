<?php

namespace app\commands\Uz;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartMonitoringDaemon extends Command
{
    public function __construct()
    {
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setName('uz:start-monitoring-daemon')
            ->setAliases(['start'])
            ->setDescription('Start uz monitoring daemon');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start monitoring'); // TODO
    }
}
