<?php

namespace app\commands\Uz;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckPlaces extends Command
{
    protected function configure()
    {
        $this
            ->setName('uz:check-places')
            ->setAliases(['check'])
            ->setDescription('Once check uz places');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Check places once'); // TODO
    }
}
