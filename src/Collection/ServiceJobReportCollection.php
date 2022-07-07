<?php

namespace App\Collection;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @var ServiceJobReport items
 */
class ServiceJobReportCollection extends ArrayCollection
{
    public function setServiceJob(ServiceJob $serviceJob)
    {
        $this->forAll(function ($key, ServiceJobReport $report) use ($serviceJob) {
            $report->setJob($serviceJob);
            return true;
        });
    }
}