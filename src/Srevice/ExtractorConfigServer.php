<?php

namespace App\Srevice;

use App\Entity\ServiceConnection;
use App\Entity\ServiceJob;

class ExtractorConfigServer
{
    /**
     * @param ServiceConnection $service
     * @return array
     */
    public function extract(ServiceConnection $service): array
    {
        $serviceConfig = $service->toArray();

        $jobConfigs = $service->getJobs()->map(function (ServiceJob $job) {
            return [
                $job->getType() => $job->toArray()
            ];
        })->toArray();

        $serviceConfig['jobs'] = $jobConfigs;
        return $serviceConfig;
    }
}