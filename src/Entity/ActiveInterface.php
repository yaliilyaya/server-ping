<?php

namespace App\Entity;

/**
 * @see ActiveTrait
 */
interface ActiveInterface
{
    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @param bool $isActive
     * @return ActiveInterface
     */
    public function setIsActive(bool $isActive): ActiveInterface;
}