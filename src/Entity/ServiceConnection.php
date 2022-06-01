<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass=ServerOptionRepository::class)
 */
class ServiceConnection
{
    const STATUS_INFO_TYPE = 'info';
    const STATUS_SUCCESS_TYPE = 'success';
    const STATUS_WARNING_TYPE = 'warning';
    const STATUS_DANGER_TYPE = 'danger';
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
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
     */
    private $commands;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
    public function getCommands(): Collection
    {
        return $this->commands ?? new ArrayCollection();
    }

    /**
     * @param Collection $commands
     */
    public function setCommands(Collection $commands): void
    {
        $this->commands = $commands;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $field
     * @return mixed|null
     */
    public function __get($field)
    {
        return $this->data[$field] ?? null;
    }

    /**
     * @param $field
     * @param $value
     * @return void
     */
    public function __set($field, $value)
    {
        $this->data[$field] = $value;
    }

    /**
     * @param $field
     * @return bool
     */
    public function __isset($field)
    {
        return isset($this->data[$field]);
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
