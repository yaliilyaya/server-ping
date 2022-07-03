<?php

namespace App\Service;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;
use App\Entity\ServiceJob;
use App\Enum\StatusEnum;
use App\Repository\ServiceCommandRepository;
use App\Repository\ServiceConnectionRepository;
use App\Repository\ServiceJobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SaveConfigServer
{
    /**
     * @var ServiceConnectionRepository
     */
    private $serviceConnectionRepository;
    /**
     * @var ServiceCommandRepository
     */
    private $serviceCommandRepository;
    /**
     * @var ServiceJobRepository
     */
    private $serviceJobRepository;

    public function __construct(
        ServiceConnectionRepository $serviceConnectionRepository,
        ServiceCommandRepository $serviceCommandRepository,
        ServiceJobRepository $serviceJobRepository
    ) {
        $this->serviceConnectionRepository = $serviceConnectionRepository;
        $this->serviceCommandRepository = $serviceCommandRepository;
        $this->serviceJobRepository = $serviceJobRepository;
    }

    /**
     * @param ServiceConnection $service
     * @param array $config
     * @param array $saveConfig
     * @return bool
     */
    public function save(
        ServiceConnection $service,
        array $config,
        array $saveConfig
    ): bool {
        $service->setName($saveConfig['name']);
        $service->setIp($saveConfig['ip']);
        $service->setStatus(StatusEnum::INFO_TYPE);
        $service->setData($saveConfig['data']);

        dump($saveConfig);
        $commands = new ArrayCollection($this->serviceCommandRepository->findAll());

        foreach ($saveConfig['jobs'] as $commandType => $jobConfig) {
            $job = $this->findJobInService($service->getJobs(), $commandType)
                ?: $this->createJob($commandType, $service, $commands);

            dump($jobConfig['data']);
            $job->setData($jobConfig['data']);
            $job->setIsActive($jobConfig['is_active'] ?? false);
            $job->setResult('');
        }
        $this->serviceConnectionRepository->save($service);

        return false;
    }

    /**
     * @param Collection $jobs
     * @param string $commandType
     * @return ServiceJob|null
     */
    private function findJobInService(Collection $jobs, string $commandType): ?ServiceJob
    {
        return $jobs->filter(function (ServiceJob $job) use ($commandType) {
            return $job->getCommand()->getType() === $commandType;
        })->current() ?: null;
    }

    /**
     * @param Collection $command
     * @param string $commandType
     * @return ServiceCommand|null
     */
    private function findCommand(Collection $command, string $commandType): ?ServiceCommand
    {
        return $command->filter(function (ServiceCommand $command) use ($commandType) {
            return $command->getType() === $commandType;
        })->current() ?: null;
    }

    /**
     * @param string $commandType
     * @param ServiceConnection $service
     * @param Collection $commands
     * @return ServiceJob
     */
    private function createJob(string $commandType, ServiceConnection $service, Collection $commands): ServiceJob
    {
        $job = new ServiceJob();

        $command = $this->findCommand($commands, $commandType);
        $job->setCommand($command);

        $service->getJobs()->add($job);
        $job->setConnection($service);

        return $job;
    }

}