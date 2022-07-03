<?php

namespace App\Service;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
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

    /**
     * @param ServiceJob $serviceJob
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport
    {
        $command = $serviceJob->getCommand();

        $runner = $this->commandRunnerFactory->create($command->getType());
        $report = $runner->run($serviceJob);

        return $report;
    }


}