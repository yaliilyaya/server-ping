<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=ServerOptionRepository::class)
 */
class ServiceConnection
{
    use IdentifierTrait;
    use DataTrait;
    use ActiveTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $ip;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $status;
    /**
     * @var Collection
     * @OneToMany(targetEntity="App\Entity\ServiceJob", mappedBy="connection", fetch="EXTRA_LAZY")
     */
    private $jobs;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Collection
     */
    public function getJobs(): Collection
    {
        return $this->jobs ?? new ArrayCollection();
    }

    /**
     * @param Collection $jobs
     */
    public function setJobs(Collection $jobs): void
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
            'name' => $this->getName(),
            'ip' => $this->getIp(),
            'status' => $this->getStatus(),
            'data' => $this->getData(),
        ];
    }
}
