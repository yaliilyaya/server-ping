<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @property string $label
 * @property string $factory
 * @property int $sizeIn
 * @property int $sizeOut
 * @property array categories
 * @property int sizeBox
 * @property float time
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    public const MAIN_RECIPE_TYPE = 'main';
    public const ALT_RECIPE_TYPE = 'alt';
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
     * @ORM\Column(type="json")
     */
    private $data = [];
    /**
     * @var Item
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="recipes")
     */
    private $item;
    /**
     * @var Collection|RecipeStream[]
     * @ORM\OneToMany(targetEntity="RecipeStream", mappedBy="recipe", cascade={"persist"})
     */
    private $recipeStreams;

    public function __construct()
    {
        $this->type = self::MAIN_RECIPE_TYPE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Recipe
     */
    public function setId($id): Recipe
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
     * @return Recipe
     */
    public function setType(string $type): Recipe
    {
        $this->type = $type;
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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Recipe
     */
    public function setLabel(string $label): Recipe
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getFactory(): string
    {
        return $this->factory;
    }

    /**
     * @param string $factory
     * @return Recipe
     */
    public function setFactory(string $factory): Recipe
    {
        $this->factory = $factory;
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
     * @return Recipe
     */
    public function setItem(Item $item): Recipe
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return Collection|RecipeStream[]
     */
    public function getRecipeStreams(): Collection
    {
        return $this->recipeStreams ?: $this->recipeStreams = new ArrayCollection();
    }

    public function getRecipeInStreams()
    {
        $recipeStreams = array_values(array_filter(
            iterator_to_array($this->recipeStreams),
            function (RecipeStream $stream) {
                return $stream->getType() === RecipeStream::IN_STREAM_TYPE;
            }
        ));

        return new ArrayCollection($recipeStreams);
    }
    public function getRecipeOutStreams()
    {
        $recipeStreams = array_values(array_filter(
            iterator_to_array($this->recipeStreams),
            function (RecipeStream $stream) {
                return $stream->getType() === RecipeStream::OUT_STREAM_TYPE;
            }
        ));

        return new ArrayCollection($recipeStreams);
    }

    /**
     * @param ArrayCollection $recipeStreams
     * @return Recipe
     */
    public function setRecipeStreams(ArrayCollection $recipeStreams): Recipe
    {
        $this->recipeStreams = $recipeStreams;
        return $this;
    }

    /**
     * @param RecipeStream[] $recipeStreams
     */
    public function addRecipeStreams(array $recipeStreams)
    {
        foreach ($recipeStreams as $stream)
        {
            $stream->setRecipe($this);
            $this->getRecipeStreams()->add($stream);
        }
    }

    /**
     * @return int
     */
    public function getSizeIn(): int
    {
        return $this->sizeIn ?: 0;
    }

    /**
     * @param int $sizeIn
     * @return Recipe
     */
    public function setSizeIn(int $sizeIn): Recipe
    {
        $this->sizeIn = $sizeIn;
        return $this;
    }

    /**
     * @return int
     */
    public function getSizeOut(): int
    {
        return $this->sizeOut ?: 0;
    }

    /**
     * @param int $sizeOut
     * @return Recipe
     */
    public function setSizeOut(int $sizeOut): Recipe
    {
        $this->sizeOut = $sizeOut;
        return $this;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories ?: [];
    }

    /**
     * @param array $categories
     * @return Recipe
     */
    public function setCategories(array $categories): Recipe
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return int
     */
    public function getSizeBox(): int
    {
        return $this->sizeBox ?: 0;
    }

    /**
     * @param int $sizeBox
     * @return Recipe
     */
    public function setSizeBox(int $sizeBox): Recipe
    {
        $this->sizeBox = $sizeBox;
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
     * @return Recipe
     */
    public function setTime(float $time): Recipe
    {
        $this->time = $time;
        return $this;
    }
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'info' => $this->data,
            'streams' => array_map(function (RecipeStream $stream) {
                return $stream->toArray();
            }, iterator_to_array($this->getRecipeStreams())),
        ];
    }
}
