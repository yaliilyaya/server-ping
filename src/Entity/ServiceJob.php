<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\ServiceJobRepository::class)
 */
class ServiceJob
{
    use IdentifierTrait;
    use DataTrait;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $result;
    /**
     * @var ServiceConnection
     * @ManyToOne(targetEntity="App\Entity\ServiceConnection", inversedBy="jobs")
     */
    private $connection;
    /**
     * @var ServiceCommand
     * @ManyToOne(targetEntity="App\Entity\ServiceConnection")
     */
    private $command;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
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
     * @return ServiceCommand
     */
    public function getCommand(): ServiceCommand
    {
        return $this->command;
    }

    /**
     * @param ServiceCommand $command
     */
    public function setCommand(ServiceCommand $command): void
    {
        $this->command = $command;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'data' => $this->getData(),
        ];
    }
}
