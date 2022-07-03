<?php

namespace App\Entity;

interface IdentifierInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;
    /**
     * @param int|null $id
     * @return IdentifierInterface
     */
    public function setId(?int $id): IdentifierInterface;
}