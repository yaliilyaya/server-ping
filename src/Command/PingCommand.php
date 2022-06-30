<?php

namespace App\Command;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Repository\ServiceJobReportRepository;
use App\Srevice\ServiceConnectionRunnerCommandService;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PingCommand implements CommandInterface
{
    /**
     * @var ServiceConnectionRunnerCommandService
     */
    private $serviceConnectionRunnerCommandService;
    /**
     * @var ServiceJobReportRepository
     */
    private $serviceJobReportRepository;

    public function __construct(
        ServiceConnectionRunnerCommandService $serviceConnectionRunnerCommandService,
        ServiceJobReportRepository $serviceJobReportRepository
    ) {
        $this->serviceConnectionRunnerCommandService = $serviceConnectionRunnerCommandService;
        $this->serviceJobReportRepository = $serviceJobReportRepository;
    }

    /**
     * @param ServiceJob $serviceJob
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport
    {
        $connection = $serviceJob->getConnection();

        $ip = $connection->getIp();

        $process = new Process(['ping', '-W 10', '-c 5', $ip]);

        try {
            $process->mustRun();
            $report = $this->serviceJobReportRepository->create();
            $report->setStatus(StatusEnum::SUCCESS_TYPE);
            $report->setResult($process->getOutput());
        } catch (ProcessFailedException $exception) {
            $message = $exception->getMessage()
                . PHP_EOL
                . $exception->getTraceAsString();

            $report = $this->serviceJobReportRepository->create();
            $report->setStatus(StatusEnum::SUCCESS_TYPE);
            $report->setResult($message);
        }

        return $report;

        die(__FILE__ . __LINE__);

        $this->serviceConnectionRunnerCommandService->execCommand($connection, $command);

        die(__FILE__ . __LINE__);
    }
}