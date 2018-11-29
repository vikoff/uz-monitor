<?php

namespace app\components\framework;

use Symfony\Component\Console\Command\Command;

class CommandsBroker
{
    /**
     * @var Command[]
     */
    private $commands = [];

    public function addCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @return Command[]
     */
    public function getCommands()
    {
        return $this->commands;
    }
}
