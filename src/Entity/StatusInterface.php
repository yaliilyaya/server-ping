<?php

namespace App\Entity;

/**
 * @see StatusTrait
 */
interface StatusInterface
{
    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return StatusInterface
     */
    public function setStatus(string $status): StatusInterface;
}