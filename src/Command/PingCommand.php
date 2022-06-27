<?php

namespace App\Command;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;
use App\Srevice\ServiceConnectionRunnerCommandService;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PingCommand implements CommandInterface
{
    /**
     * @var ServiceConnectionRunnerCommandService
     */
    private $serviceConnectionRunnerCommandService;

    public function __construct(
        ServiceConnectionRunnerCommandService $serviceConnectionRunnerCommandService
    ) {
        $this->serviceConnectionRunnerCommandService = $serviceConnectionRunnerCommandService;
    }

    public function run(ServiceJob $serviceJob)
    {
        $connection = $serviceJob->getConnection();

        $ip = $connection->getIp();

        $process = new Process(['ping', '-W 10', '-c 5', $ip]);

        try {
            $process->mustRun();

            echo '<pre>' . $process->getOutput() . '</pre>';
        } catch (ProcessFailedException $exception) {
            echo '<pre>' . $exception->getMessage() . '</pre>';
        }


        die(__FILE__ . __LINE__);

        $this->serviceConnectionRunnerCommandService->execCommand($connection, $command);

        die(__FILE__ . __LINE__);
    }
}