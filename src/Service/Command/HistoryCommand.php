<?php

namespace App\Service\Command;

use App\Collection\ServiceJobReportCollection;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Model\RemoteFileCommand;
use App\Repository\ServiceJobReportRepository;
use App\Service\CommandRunnerService;
use Doctrine\Common\Collections\ArrayCollection;

class HistoryCommand implements CommandInterface
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
     * @param ServiceJobReportRepository $serviceJobReportRepository
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

        $remoteFileCommand = new RemoteFileCommand();

        $remoteFileCommand->setFilePath('~/.bash_history');
        $remoteFileCommand->setConnectParam([
            $connection->getUser(),
            $connection->getIp(),
            $connection->getPassword()
        ]);

        $report = $this->commandRunnerService->run($remoteFileCommand);

        if ($report->getStatus() !== StatusEnum::SUCCESS_TYPE) {
            return new ServiceJobReportCollection([$report]);
        }

        $content = file_get_contents($remoteFileCommand->getTmpFilePath());

        $report = $this->serviceJobReportRepository->create();
        $report->setStatus(StatusEnum::SUCCESS_TYPE);
        $report->setResult($content);

        return new ServiceJobReportCollection([$report]);
    }
}