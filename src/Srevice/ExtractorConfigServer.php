<?php

namespace App\Srevice;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;

class ExtractorConfigServer
{
    /**
     * @param ServiceConnection $service
     * @return array
     */
    public function extract(ServiceConnection $service): array
    {
        $serviceConfig = $service->toArray();

        $commands = $service->getCommands();
        $commandConfigs = $commands->map(function (ServiceCommand $command) {
            return [
                $command->getType() => $command->toArray()
            ];
        })->toArray();

        $serviceConfig['commands'] = $commandConfigs;
        return $serviceConfig;
    }
}