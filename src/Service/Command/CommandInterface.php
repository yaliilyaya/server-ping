<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;

interface CommandInterface
{
    /**
     * @param ServiceJob $serviceJob
     * @return ServiceJobReport
     */
    public function run(ServiceJob $serviceJob): ServiceJobReport;
}