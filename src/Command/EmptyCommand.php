<?php

namespace App\Command;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;

class EmptyCommand implements CommandInterface
{
    public function run(ServiceJob $serviceJob)
    {
    }
}