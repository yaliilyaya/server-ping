<?php

namespace App\Command;

use App\Entity\ServiceJob;

interface CommandInterface
{
    /**
     * @param ServiceJob $serviceJob
     * @return mixed
     */
    public function run(ServiceJob $serviceJob);
}