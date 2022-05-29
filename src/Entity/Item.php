<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
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
class Item
{
    public const NEW_STATUS_TYPE = 'new';
    public const RECIPE_STATUS_TYPE = 'recipe';
    public const ERROR_STATUS_TYPE = 'error';

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
    private $status;
    /**
     * @var string
     * @ORM\Column(type="string", options={"default" : ""})
     */
    private $label;
    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $data = [];
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Recipe", mappedBy="item",  cascade={"persist"})
     */
    private $recipes;

    public function __construct()
    {
        $this->status = self::NEW_STATUS_TYPE;
        $this->label = '';
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Item
     */
    public function setStatus(string $status): Item
    {
        $this->status = $status;
        return $this;
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

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     * @return Item
     */
    public function setImg(string $img): Item
    {
        $this->img = $img;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Item
     */
    public function setUrl(string $url): Item
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Item
     */
    public function setLabel(string $label): Item
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     * @return Item
     */
    public function setDetail(string $detail): Item
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category ?: '';
    }

    /**
     * @param string $category
     * @return Item
     */
    public function setCategory(string $category): Item
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getStackSize(): string
    {
        return $this->stackSize ?: 0;
    }

    /**
     * @param string $stackSize
     * @return Item
     */
    public function setStackSize(string $stackSize): Item
    {
        $this->stackSize = $stackSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalvaging(): string
    {
        return $this->salvaging;
    }

    /**
     * @param string $salvaging
     * @return Item
     */
    public function setSalvaging(string $salvaging): Item
    {
        $this->salvaging = $salvaging;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight ?: 0.0;
    }

    /**
     * @return int
     */
    public function getMaxCount(): int
    {
        return $this->maxCount ?: 0;
    }

    /**
     * @param int $maxCount
     * @return Item
     */
    public function setMaxCount(int $maxCount): Item
    {
        $this->maxCount = $maxCount;
        return $this;
    }

    /**
     * @param float $weight
     * @return Item
     */
    public function setWeight(float $weight): Item
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getRecipes(): Collection
    {
        return $this->recipes ?: $this->recipes = new ArrayCollection();
    }

    /**
     * @param ArrayCollection $recipes
     * @return Item
     */
    public function setRecipes(ArrayCollection $recipes): Item
    {
        $this->recipes = $recipes;
        return $this;
    }

    /**
     * @return float
     */
    public function getStackWeight(): float
    {
        return $this->stackWeight;
    }

    /**
     * @param float $stackWeight
     * @return Item
     */
    public function setStackWeight(float $stackWeight): Item
    {
        $this->stackWeight = $stackWeight;
        return $this;
    }

    /**
     * Путь до публичной папки с изображениями
     * @return string
     */
    public function getImgPath(): string
    {
        return 'img/img_' . $this->getId() . '.png';
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'info' => $this->data,
            'label' => $this->label,
            'recipes' => array_map(function (Recipe $recipe) {
                return $recipe->toArray();
            }, iterator_to_array($this->getRecipes())),
        ];
    }
}
