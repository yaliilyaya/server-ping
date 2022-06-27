<?php

namespace App\Srevice;

use App\Entity\ServiceJob;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JobRunnerService
{
    /**
     * @var \App\Factory\CommandRunnerFactory
     */
    private $commandRunnerFactory;

    public function __construct(\App\Factory\CommandRunnerFactory $commandRunnerFactory)
    {

        $this->commandRunnerFactory = $commandRunnerFactory;
    }

    public function run(ServiceJob $serviceJob)
    {
        $command = $serviceJob->getCommand();

        $runner = $this->commandRunnerFactory->create($command->getType());
        $runner->run($serviceJob);
    }
}