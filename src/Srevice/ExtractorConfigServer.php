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
     * @var ServiceCommandRepository
     */
    private $serviceCommandRepository;

    public function __construct(ServiceCommandRepository $serviceCommandRepository)
    {

        $this->serviceCommandRepository = $serviceCommandRepository;
    }

    /**
     * @param ServiceConnection $service
     * @return array
     */
    public function extract(ServiceConnection $service): array
    {
        $serviceConfig = $service->toArray();

        $commandConfigs = $this->extractCommandConfigs($this->serviceCommandRepository->findAll());
        $jobConfigs = $this->extractJobConfigs($service->getJobs());

        dump($commandConfigs);
        dump($jobConfigs);

        $serviceConfig['jobs'] = $jobConfigs;
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

    /**
     * @param array $commands
     * @return array
     */
    private function extractCommandConfigs(array $commands): array
    {
        $commandCollection = new ArrayCollection($commands);

        $commandConfigs = $commandCollection->map(function (ServiceCommand $command) {
            return [
                $command->getType() => $command->toArray()
            ];
        })->toArray();

        return $commandConfigs ? array_merge(...$commandConfigs) : [];
    }
}