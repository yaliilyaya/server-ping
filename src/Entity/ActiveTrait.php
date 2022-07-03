<?php


namespace App\Entity;

/**
 * @see ActiveInterface
 */
trait ActiveTrait
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive === true;
    }

    /**
     * @param bool $isActive
     * @return ActiveInterface
     */
    public function setIsActive(bool $isActive): ActiveInterface
    {
        $this->isActive = $isActive;
        return $this;
    }
}