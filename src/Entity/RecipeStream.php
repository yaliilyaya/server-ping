<?php

namespace App\Entity;

use App\Repository\RecipeStreamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @property string $message
 * @property string $label
 * @property string $countTime
 * @property string $count
 * @property string $countTimeType
 * @property string itemType
 * @property float weight
 * @property int countCell
 * @property float time
 * @ORM\Entity(repositoryClass=RecipeStreamRepository::class)
 */
class RecipeStream
{
    public const IN_STREAM_TYPE = 'in';
    public const OUT_STREAM_TYPE = 'out';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var Recipe
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="recipeStreams")
     */
    private $recipe;
    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $data = [];
    /**
     * @var Item
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="recipeStreams")
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return RecipeStream
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return RecipeStream
     */
    public function setType(string $type): RecipeStream
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Recipe
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     * @return RecipeStream
     */
    public function setRecipe(Recipe $recipe): RecipeStream
    {
        $this->recipe = $recipe;
        return $this;
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
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return RecipeStream
     */
    public function setMessage(string $message): RecipeStream
    {
        $this->message = $message;
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
     * @return RecipeStream
     */
    public function setLabel(string $label): RecipeStream
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountTime(): string
    {
        return $this->countTime;
    }

    /**
     * @param string $countTime
     * @return RecipeStream
     */
    public function setCountTime(string $countTime): RecipeStream
    {
        $this->countTime = $countTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getCount(): string
    {
        return $this->count;
    }

    /**
     * @param string $count
     * @return RecipeStream
     */
    public function setCount(string $count): RecipeStream
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountTimeType(): string
    {
        return $this->countTimeType;
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->itemType ?: 'шт';
    }

    /**
     * @param string $itemType
     * @return RecipeStream
     */
    public function setItemType(string $itemType): RecipeStream
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * @param string $countTimeType
     * @return RecipeStream
     */
    public function setCountTimeType(string $countTimeType): RecipeStream
    {
        $this->countTimeType = $countTimeType;
        return $this;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return RecipeStream
     */
    public function setItem(Item $item): RecipeStream
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight ?: 0;
    }

    /**
     * @param float $weight
     * @return RecipeStream
     */
    public function setWeight(float $weight): RecipeStream
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountCell(): int
    {
        return $this->countCell ?: 0;
    }

    /**
     * @param int $countCell
     * @return RecipeStream
     */
    public function setCountCell(int $countCell): RecipeStream
    {
        $this->countCell = $countCell;
        return $this;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time ?: 0.0;
    }

    /**
     * @param float $time
     * @return RecipeStream
     */
    public function setTime(float $time): RecipeStream
    {
        $this->time = $time;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'item' => $this->item->getLabel(),
            'itemId' => $this->item->getId(),
            'info' => $this->data,
        ];
    }
}
