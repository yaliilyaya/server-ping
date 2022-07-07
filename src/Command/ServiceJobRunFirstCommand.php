<?php
// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Repository\ServiceJobReportRepository;
use App\Repository\ServiceJobRepository;
use App\Service\JobRunnerService;
use App\Service\ServiceJobResultSaveService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
class ServiceJobRunFirstCommand extends Command
{
    protected static $defaultName = 'service-job:run-first';
    /**
     * @var int
     */
    private $sleepTime = 10000000;
    /**
     * @var JobRunnerService
     */
    private $jobRunnerService;
    /**
     * @var ServiceJobRepository
     */
    private $serviceJobRepository;

    /**
     * @var ServiceJobResultSaveService
     */
    private $serviceJobResultSaveService;

    public function __construct(
        string $name = null,
        JobRunnerService $jobRunnerService,
        ServiceJobRepository $serviceJobRepository,
        ServiceJobResultSaveService $serviceJobResultSaveService
    ) {
        parent::__construct($name);
        $this->jobRunnerService = $jobRunnerService;
        $this->serviceJobRepository = $serviceJobRepository;
        $this->serviceJobResultSaveService = $serviceJobResultSaveService;
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('Запускает первую активную команду или делает задержку')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $serviceJob = $this->serviceJobRepository->findFirstActive();
        if (!$serviceJob) {

            $sleepTime = $this->sleepTime / 1000000;
            $output->writeln("status => success");
            $output->writeln("sleep => {$sleepTime}");
            usleep($this->sleepTime);

            return Command::SUCCESS;
        }
        do {
            $reports = $this->jobRunnerService->run($serviceJob);
            $this->serviceJobResultSaveService->save($serviceJob, $reports);

            $output->writeln("jobId => {$serviceJob->getId()}");
            $output->writeln("commandType => {$serviceJob->getCommand()->getType()}");
            $output->writeln("connectionIp => {$serviceJob->getConnection()->getIp()}");
            $output->writeln("status => {$serviceJob->getStatus()}");
        } while ($serviceJob = $this->serviceJobRepository->findFirstActive());


        return Command::SUCCESS;
    }

    /**
     * @param ServiceJobReport $report
     * @return string
     */
    private function extractStatus(ServiceJobReport $report): string
    {
        $status = $report->getStatus();

        return $status === StatusEnum::SUCCESS_TYPE
            ? StatusEnum::SUCCESS_TYPE
            : StatusEnum::ERROR_TYPE;
    }
}