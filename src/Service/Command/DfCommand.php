<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\RemoteCommand;
use App\Service\CommandRunnerService;
use Doctrine\Common\Collections\ArrayCollection;

class DfCommand implements CommandInterface
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
     * @return ArrayCollection
     */
    public function run(ServiceJob $serviceJob): ArrayCollection
    {
        $connection = $serviceJob->getConnection();

        $remoteFileCommand = new RemoteCommand();

        $remoteFileCommand->setCommandAttribute(['df', '-h']);
        $remoteFileCommand->setConnectParam([
            $connection->getUser(),
            $connection->getIp(),
            $connection->getPassword()
        ]);

        $report = $this->commandRunnerService->run($remoteFileCommand);

        return new ArrayCollection([$report]);
    }
}