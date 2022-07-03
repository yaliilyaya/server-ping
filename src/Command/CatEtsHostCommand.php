<?php

namespace App\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\Command;
use App\Model\RemoteCommand;
use App\Srevice\CommandRunnerService;
use App\Srevice\RemoteCommandRunnerService;

class CatEtsHostCommand implements CommandInterface
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

        $command = new RemoteCommand();

        $command->setCommandAttribute(['cat', '/etc/hosts']);
        $command->setConnectParam([
            $connection->getUser(),
            $connection->getIp(),
            $connection->getPassword()
        ]);

//        echo "<pre>" . print_r([
//                implode(" ", $command->getCommand())
//            ], 1) . "</pre>";
//
//        die(__FILE__ . __LINE__);
        return $this->commandRunnerService->run($command);
    }
}