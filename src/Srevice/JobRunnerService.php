<?php

namespace App\Srevice;

use App\Entity\ServiceJob;
use App\Factory\CommandRunnerFactory;

class JobRunnerService
{
    /**
     * @var CommandRunnerFactory
     */
    private $commandRunnerFactory;

    public function __construct(CommandRunnerFactory $commandRunnerFactory)
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