<?php

namespace App\Srevice;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;
use App\Entity\ServiceJob;
use App\Repository\ServiceCommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ExtractorConfigServer
{
    /**
     * @param ServiceConnection $service
     * @return array
     */
    public function extract(ServiceConnection $service): array
    {
        $serviceConfig = $service->toArray();
        $serviceConfig['jobs'] = $this->extractJobConfigs($service->getJobs());
        return $serviceConfig;
    }

    /**
     * @param Collection $jobs
     * @return array
     */
    private function extractJobConfigs(Collection $jobs): array
    {
        $jobConfigs = $jobs->map(function (ServiceJob $job) {
            return [
                $job->getCommand()->getType() => $job->toArray()
            ];
        })->toArray();

        return $jobConfigs ? array_merge(...$jobConfigs) : [];
    }
}