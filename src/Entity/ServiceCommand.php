<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\OneToMany;

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

    /**
     * @var Collection|ServiceJob[]
     * @see ServiceJob::command
     * @OneToMany(targetEntity="\App\Entity\ServiceJob", mappedBy="conmmand", fetch="EXTRA_LAZY")
     */
    private $jobs;

    public function __construct()
    {
        $this->setIsActive(false);
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
     * @return ServiceJob[]|Collection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param ServiceJob[]|Collection $jobs
     */
    public function setJobs($jobs): void
    {
        $this->jobs = $jobs;
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
