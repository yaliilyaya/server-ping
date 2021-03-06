<?php

namespace App\Entity;

use App\Collection\ServiceJobReportCollection;
use App\Enum\StatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;

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
     * @var ServiceConnection|null
     * @see ServiceConnection::jobs
     * @ManyToOne(targetEntity="App\Entity\ServiceConnection", inversedBy="jobs")
     */
    private $connection;
    /**
     * @var ServiceCommand|null
     * @see ServiceCommand::jobs
     * @ManyToOne(targetEntity="App\Entity\ServiceCommand", inversedBy="jobs")
     */
    private $command;
    /**
     * @var ServiceJobReportCollection
     * @see ServiceJobReport::job
     * @OneToMany(targetEntity="\App\Entity\ServiceJobReport", mappedBy="job", fetch="EXTRA_LAZY", cascade={"persist"})
     */
    private $reports;

    public function __construct()
    {
        $this->setStatus(StatusEnum::DEFAULT_TYPE);
        $this->setIsActive(false);
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
     * @return ServiceConnection|null
     */
    public function getConnection(): ?ServiceConnection
    {
        return $this->connection;
    }

    /**
     * @param ServiceConnection|null $connection
     */
    public function setConnection(?ServiceConnection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @return ServiceCommand|null
     */
    public function getCommand(): ?ServiceCommand
    {
        return $this->command;
    }

    /**
     * @param ServiceCommand|null $command
     */
    public function setCommand(?ServiceCommand $command): void
    {
        $this->command = $command;
    }

    /**
     * @return ServiceJobReportCollection
     */
    public function getReports(): ServiceJobReportCollection
    {
        return $this->reports instanceof ServiceJobReportCollection
            ? $this->reports
            : new ServiceJobReportCollection($this->reports->toArray());
    }

    /**
     * @param ServiceJobReportCollection $reports
     */
    public function setReports(ServiceJobReportCollection $reports): void
    {
        $this->reports = $reports;
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
