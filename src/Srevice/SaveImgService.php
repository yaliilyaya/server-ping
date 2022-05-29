<?php

namespace App\Srevice;

use App\Entity\Item;
use Symfony\Component\HttpKernel\KernelInterface;

class SaveImgService
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function saveItemImg(Item $item): bool
    {
        $fileName = $this->kernel->getProjectDir() . '/public/' . $item->getImgPath();
        $fileExists = file_exists($fileName);

        return $fileExists
            || file_put_contents($fileName, file_get_contents($item->getImg()));
    }
}