<?php

namespace App\Command;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;

class PingCommand implements CommandInterface
{
    public function run(ServiceJob $serviceJob)
    {
        die(__FILE__ . __LINE__);
    }
}