<?php

namespace App\Command;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Repository\ServiceJobReportRepository;

class EmptyCommand implements CommandInterface
{
    /**
     * @var ServiceJobReportRepository
     */
    private $serviceJobReportRepository;

    /**
     * @param ServiceJobReportRepository $serviceJobReportRepository
     */
    public function __construct(ServiceJobReportRepository $serviceJobReportRepository)
    {
        $this->serviceJobReportRepository = $serviceJobReportRepository;
    }

    /**
     * @param ServiceJob $serviceJob
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport
    {
        return $this->serviceJobReportRepository->create();
    }
}