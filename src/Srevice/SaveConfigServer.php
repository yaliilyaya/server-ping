<?php

namespace App\Srevice;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;
use App\Entity\ServiceJob;
use App\Enum\StatusEnum;
use App\Repository\ServiceConnectionRepository;

class SaveConfigServer
{
    /**
     * @var ServiceConnectionRepository
     */
    private $serviceConnectionRepository;

    public function __construct(ServiceConnectionRepository $serviceConnectionRepository)
    {
        $this->serviceConnectionRepository = $serviceConnectionRepository;
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

        $this->serviceConnectionRepository->save($service);

        return false;
    }
}