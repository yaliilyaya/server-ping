<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass=ServerOptionRepository::class)
 */
class ServiceJob
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
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
     * @var ServiceCommand
     * @ ORM\Column(type="string")
     */
    private $command;

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
        return $this->id ?? 0;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data ?? [];
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
            'data' => $this->getData(),
        ];
    }
}
