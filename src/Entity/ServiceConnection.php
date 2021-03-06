<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @property string $user
 * @property string $password
 * @ORM\Entity(repositoryClass=\App\Repository\ServiceConnectionRepository::class)
 */
class ServiceConnection implements
    IdentifierInterface,
    DataInterface,
    ActiveInterface,
    StatusInterface
{
    use IdentifierTrait;
    use DataTrait;
    use ActiveTrait;
    use StatusTrait;

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
     * @var Collection|ServiceJob[]
     * @see ServiceJob::connection
     * @OneToMany(targetEntity="\App\Entity\ServiceJob", mappedBy="connection", fetch="EXTRA_LAZY")
     */
    private $jobs;

    public function __construct()
    {
        $this->setStatus(StatusEnum::DEFAULT_TYPE);
        $this->setIsActive(false);
        $this->setUser('developer');
        $this->setPassword('developer');
    }

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
     * @return string
     */
    public function getUser(): string
    {
        return $this->user ?? '';
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
