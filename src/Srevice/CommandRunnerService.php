<?php

namespace App\Srevice;

use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Model\Command;
use App\Model\CommandInterface;
use App\Repository\ServiceJobReportRepository;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CommandRunnerService
{
    /**
     * @var ServiceJobReportRepository
     */
    private $serviceJobReportRepository;

    /**
     * @param ServiceJobReportRepository $serviceJobReportRepository
     */
    public function __construct(
        ServiceJobReportRepository $serviceJobReportRepository
    ) {
        $this->serviceJobReportRepository = $serviceJobReportRepository;
    }

    /**
     * @param CommandInterface $command
     * @return ServiceJobReport
     */
    public function run(CommandInterface $command): ServiceJobReport
    {
        echo "<pre>" . print_r([
                implode(" ",$command->getCommand())
            ], 1) . "</pre>";
        die(__FILE__ . __LINE__);
        $process = new Process($command->getCommand());

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

    }
}