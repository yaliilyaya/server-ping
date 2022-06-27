<?php

namespace App\Srevice;

use App\Entity\ServiceConnection;

class ServiceConnectionRunnerCommandService
{
    public function execCommand(ServiceConnection $connection, string $command)
    {
        $ip = $connection->getIp();
        $user = $connection->getUser();
        $password = $connection->getPassword();

        $connectCommand = "ssh {$user}@{$ip} bash -c \"{$command}\"";
    }
}