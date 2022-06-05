<?php

namespace App\Srevice;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;
use App\Enum\StatusEnum;

class SaveConfigServer
{
    /**
     * @param ServiceCommand $service
     * @return bool
     */
    public function save(
        ServiceCommand $service,
                       $config,
                       $saveConfig
    ): bool {
        $service->setName($saveConfig['name']);
        $service->setIp($saveConfig['ip']);
        $service->setStatus(StatusEnum::DEFAULT_TYPE);
        $service->setData($saveConfig['data']);

        dump($service);
        dump($config);
        dump($saveConfig);
       return true;
    }
}