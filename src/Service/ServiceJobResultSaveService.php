<?php

namespace App\Service;

use App\Collection\ServiceJobReportCollection;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Repository\ServiceJobReportRepository;
use App\Repository\ServiceJobRepository;

class ServiceJobResultSaveService
{
    /**
     * @var ServiceJobRepository
     */
    private $serviceJobRepository;
    /**
     * @var ServiceJobReportRepository
     */
    private $serviceJobReportRepository;

    public function __construct(
        ServiceJobRepository $serviceJobRepository,
        ServiceJobReportRepository $serviceJobReportRepository
    ) {

        $this->serviceJobRepository = $serviceJobRepository;
        $this->serviceJobReportRepository = $serviceJobReportRepository;
    }

    /**
     * @param ServiceJob $serviceJob
     * @param ServiceJobReportCollection $reports
     * @return void
     */
    public function save(ServiceJob $serviceJob, ServiceJobReportCollection $reports)
    {
        $this->serviceJobReportRepository->removeAll($serviceJob->getReports());

        $reports->setServiceJob($serviceJob);
        $serviceJob->setReports($reports);

        $status = $this->extractStatus($reports->current());
        $serviceJob->setStatus($status);


        $this->serviceJobRepository->save($serviceJob);
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