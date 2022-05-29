<?php

namespace App\Srevice;

use App\Entity\Item;
use Symfony\Component\HttpKernel\KernelInterface;

class BuilderAtlasImagesService
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
     * @param Item[] $items
     * @return void
     */
    public function create(array $items)
    {
        $item = current($items);
        $fullPathImage =  $this->kernel->getProjectDir() . '/public/' . $item->getImgPath();
        $dest = imagecreate( 256 * 2, 256* 2);
        $src = imagecreatefrompng($fullPathImage);

        imagealphablending($dest, true);
        imagesavealpha($dest, true);

        imagealphablending($src, true);
        imagesavealpha($src, true);

        imagecopymerge($dest , $src, 30, 30, 0, 0, 256, 256, 100);

        header('Content-Type: image/png');
        imagepng($dest);

        imagedestroy($dest);
        imagedestroy($src);

        die();
    }
}