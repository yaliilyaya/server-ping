<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Repository\ServiceJobReportRepository;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @return ArrayCollection
     */
    public function run(ServiceJob $serviceJob): ArrayCollection
    {
        $report = $this->serviceJobReportRepository->create();

        return new ArrayCollection([$report]);
    }
}