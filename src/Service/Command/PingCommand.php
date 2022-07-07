<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\Command;
use App\Service\CommandRunnerService;
use Doctrine\Common\Collections\ArrayCollection;

class PingCommand implements CommandInterface
{
    /**
     * @var CommandRunnerService
     */
    private $localCommandRunnerService;

    /**
     * @param CommandRunnerService $localCommandRunnerService
     */
    public function __construct(
        CommandRunnerService $localCommandRunnerService
    ) {
        $this->localCommandRunnerService = $localCommandRunnerService;
    }

    /**
     * @param ServiceJob $serviceJob
     * @return ArrayCollection
     */
    public function run(ServiceJob $serviceJob): ArrayCollection
    {
        $connection = $serviceJob->getConnection();
        $ip = $connection->getIp();

        $command = new Command();
        $command->setCommandAttribute(['ping', '-W 10', '-c 5', $ip]);


        $report = $this->localCommandRunnerService->run($command);

        return new ArrayCollection([$report]);
    }
}