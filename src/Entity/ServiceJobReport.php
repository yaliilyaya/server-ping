<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\ServiceJobRepository::class)
 */
class ServiceJobReport implements
    IdentifierInterface,
    StatusInterface
{
    use IdentifierTrait;
    use StatusTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $result;

    public function __construct()
    {
        $this->setStatus(StatusEnum::DEFAULT_TYPE);
        $this->setResult('');
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result ?? '';
    }

    /**
     * @param string $result
     */
    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'result' => $this->getResult(),
        ];
    }
}
