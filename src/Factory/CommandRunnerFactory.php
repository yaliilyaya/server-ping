<?php

namespace App\Factory;

use App\Command\CommandInterface;
use App\Command\EmptyCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class CommandRunnerFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var EmptyCommand
     */
    private $emptyCommand;

    public function __construct(
        ContainerInterface $container,
        EmptyCommand $emptyCommand
    ) {

        $this->container = $container;
        $this->emptyCommand = $emptyCommand;
    }

    /**
     * @param $type
     * @return CommandInterface
     */
    public function create($type): CommandInterface
    {
        try {
            $runnerName = 'command.' . $type;
            $runner = $this->container->get($runnerName);

            return $runner instanceof CommandInterface
                ? $runner
                : $this->emptyCommand;

        } catch (ServiceNotFoundException $exception) {
            return $this->emptyCommand;
        }
    }
}