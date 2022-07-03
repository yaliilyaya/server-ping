<?php

namespace App\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\Command;
use App\Srevice\CommandRunnerService;

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
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport
    {
        $connection = $serviceJob->getConnection();
        $ip = $connection->getIp();

        $command = new Command();
        $command->setCommandAttribute(['ping', '-W 10', '-c 5', $ip]);

        return $this->localCommandRunnerService->run($command);
    }
}