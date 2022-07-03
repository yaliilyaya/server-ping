<?php

namespace App\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Model\RemoteCommand;
use App\Model\RemoteFileCommand;
use App\Repository\ServiceJobReportRepository;
use App\Srevice\CommandRunnerService;

class PhpModuleCommand implements CommandInterface
{

    /**
     * @var CommandRunnerService
     */
    private $commandRunnerService;

    /**
     * @param CommandRunnerService $commandRunnerService
     */
    public function __construct(
        CommandRunnerService $commandRunnerService
    ) {
        $this->commandRunnerService = $commandRunnerService;
    }

    /**
     * @param ServiceJob $serviceJob
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport
    {
        $connection = $serviceJob->getConnection();

        $remoteFileCommand = new RemoteCommand();

        $remoteFileCommand->setCommandAttribute(['php', '-m']);
        $remoteFileCommand->setConnectParam([
            $connection->getUser(),
            $connection->getIp(),
            $connection->getPassword()
        ]);

        return $this->commandRunnerService->run($remoteFileCommand);
    }
}