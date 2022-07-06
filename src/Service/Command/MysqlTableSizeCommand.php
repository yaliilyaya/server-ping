<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Model\Command;
use App\Service\CommandRunnerService;
use Doctrine\Persistence\ManagerRegistry;

class MysqlTableSizeCommand implements CommandInterface
{
    /**
     * @param ServiceJob $serviceJob
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport
    {
        $connection = $serviceJob->getConnection();
        $ip = $connection->getIp();


        echo "<pre>" . print_r([
                $ip,
                $serviceJob->getData()
            ], 1) . "</pre>";

        die(__FILE__ . __LINE__);

        $command = new Command();
        $command->setCommandAttribute(['ping', '-W 10', '-c 5', $ip]);

        $report =  $this->localCommandRunnerService->run($command);



        echo "<pre>" . print_r([
                $report->getResult()
            ], 1) . "</pre>";
        die(__FILE__ . __LINE__);
        return $report;
    }
}