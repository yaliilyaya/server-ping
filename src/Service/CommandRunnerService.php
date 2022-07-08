<?php

namespace App\Service;

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
        $stringCommand = implode(" ", $command->getCommand());

        $process = new Process($command->getCommand());

        $report = $this->serviceJobReportRepository->create();
        $report->setCommand($stringCommand);

        try {
            $process->mustRun();
            $report->setStatus(StatusEnum::SUCCESS_TYPE);
            $report->setResult($process->getOutput());
        } catch (ProcessFailedException $exception) {
            $message = $exception->getMessage()
                . PHP_EOL
                . $exception->getTraceAsString();

            $report->setStatus(StatusEnum::ERROR_TYPE);
            $report->setResult($message);
        }

        return $report;

    }
}