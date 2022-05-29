<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerOptionRepository::class)
 * @property string $img
 * @property string $url
 * @property string $detail
 * @property string $category
 * @property string $stackSize
 * @property string $salvaging
 * @property float $weight
 * @property int maxCount
 * @property float stackWeight
 */
class ServiceConnection
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $data = [];

    public function __construct()
    {
    }

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

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function __get($field)
    {
        return $this->data[$field] ?? null;
    }

    public function __set($field, $value)
    {
        $this->data[$field] = $value;
    }

    public function __isset($field)
    {
        return isset($this->data[$field]);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
        ];
    }
}
