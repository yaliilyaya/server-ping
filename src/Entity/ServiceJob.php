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
class ServiceJob implements
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
    private $result;
    /**
     * @var ServiceConnection
     * @see ServiceConnection::jobs
     * @ManyToOne(targetEntity="App\Entity\ServiceConnection", inversedBy="jobs")
     */
    private $connection;
    /**
     * @var ServiceCommand
     * @OneToOne(targetEntity="App\Entity\ServiceCommand")
     */
    private $command;

    public function __construct()
    {
        $this->setStatus(StatusEnum::DEFAULT_TYPE);
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
     * @return ServiceConnection
     */
    public function getConnection(): ServiceConnection
    {
        return $this->connection;
    }

    /**
     * @param ServiceConnection $connection
     */
    public function setConnection(ServiceConnection $connection): void
    {
        $this->connection = $connection;
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
            'is_active' => $this->isActive(),
            'data' => $this->getData(),
            'command' => $this->command ? $this->getCommand()->toArray() : null,
            'result' => $this->getResult(),
        ];
    }
}
