<?php

namespace App\Service\Command;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use Doctrine\Common\Collections\ArrayCollection;

interface CommandInterface
{
    /**
     * @param ServiceJob $serviceJob
     * @return ArrayCollection
     */
    public function run(ServiceJob $serviceJob): ArrayCollection;
}