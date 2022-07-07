<?php

namespace App\Service;

use App\Collection\ServiceJobReportCollection;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Factory\CommandRunnerFactory;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @return ServiceJobReportCollection
     */
    public function run(ServiceJob $serviceJob): ServiceJobReportCollection
    {
        $command = $serviceJob->getCommand();

        $runner = $this->commandRunnerFactory->create($command->getType());
        return $runner->run($serviceJob);
    }


}