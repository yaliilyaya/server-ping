<?php

namespace App\Service\Command;

use App\Collection\ServiceJobReportCollection;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Model\RemoteCommand;
use App\Repository\ServiceJobReportRepository;
use App\Service\CommandRunnerService;
use Doctrine\Common\Collections\ArrayCollection;

class TailCommand implements CommandInterface
{

    /**
     * @var CommandRunnerService
     */
    private $commandRunnerService;
    /**
     * @var ServiceJobReportRepository
     */
    private $serviceJobReportRepository;

    /**
     * @param CommandRunnerService $commandRunnerService
     */
    public function __construct(
        CommandRunnerService $commandRunnerService,
        ServiceJobReportRepository $serviceJobReportRepository
    ) {
        $this->commandRunnerService = $commandRunnerService;
        $this->serviceJobReportRepository = $serviceJobReportRepository;
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

        if (!$files) {
            $report = $this->serviceJobReportRepository->create();
            $report->setStatus(StatusEnum::ERROR_TYPE);
            $report->setResult('Empty $files');
            return  new ServiceJobReportCollection([$report]);
        }

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