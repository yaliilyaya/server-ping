<?php


namespace App\Entity;


trait IdentifierTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return IdentifierInterface
     */
    public function setId(?int $id): IdentifierInterface
    {
        $this->id = $id;
        return $this;
    }
}