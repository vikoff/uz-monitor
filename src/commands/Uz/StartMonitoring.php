<?php

namespace app\commands\Uz;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartMonitoring extends Command
{
    protected function configure()
    {
        $this
            ->setName('uz:start-monitoring')
            ->setAliases(['start'])
            ->setDescription('Start uz monitoring');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start monitoring'); // TODO
    }
}
