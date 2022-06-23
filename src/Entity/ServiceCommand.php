<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\ServiceCommandRepository::class)
 */
class ServiceCommand implements
    IdentifierInterface,
    DataInterface,
    ActiveInterface
{
    use IdentifierTrait;
    use DataTrait;
    use ActiveTrait;

    public function __construct()
    {
        $this->isActive = false;
    }

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type ?? '';
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'is_active' => $this->isActive(),
            'data' => $this->getData(),
        ];
    }
}
