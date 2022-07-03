<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\RemoteCommand;
use App\Service\CommandRunnerService;

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