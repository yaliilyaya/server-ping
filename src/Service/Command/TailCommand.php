<?php

namespace App\Service\Command;

use App\Collection\ServiceJobReportCollection;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\RemoteCommand;
use App\Service\CommandRunnerService;
use Doctrine\Common\Collections\ArrayCollection;

class TailCommand implements CommandInterface
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
     * @return ServiceJobReportCollection
     */
    public function run(ServiceJob $serviceJob): ServiceJobReportCollection
    {
        $connection = $serviceJob->getConnection();

        $data = $serviceJob->getData();
        $files = $data['files'] ?? [];

        $params = [
            $connection->getUser(),
            $connection->getIp(),
            $connection->getPassword()
        ];

        $reports = new ServiceJobReportCollection();

        foreach ($files as $file) {
            $report = $this->execRemoteFileContent($file, $params);
            $reports->add($report);
        }

        return $reports;
    }

    /**
     * @param $file
     * @param array $params
     * @return ServiceJobReport
     */
    private function execRemoteFileContent($file, array $params): ServiceJobReport
    {
        $remoteFileCommand = new RemoteCommand();

        $remoteFileCommand->setCommandAttribute(['tail', '-n 10', $file]);
        $remoteFileCommand->setConnectParam($params);

        return $this->commandRunnerService->run($remoteFileCommand);
    }
}