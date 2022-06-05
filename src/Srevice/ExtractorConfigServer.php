<?php

namespace App\Srevice;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;

class ExtractorConfigServer
{
    /**
     * @param ServiceCommand $service
     * @return array
     */
    public function extract(ServiceCommand $service): array
    {
        $serviceConfig = $service->toArray();

        $jobConfigs = $service->getJobs()->map(function (ServiceJob $job) {
            return [
                $job->getCommand()->getType() => $job->toArray()
            ];
        })->toArray();

        $serviceConfig['jobs'] = $jobConfigs;
        return $serviceConfig;
    }
}