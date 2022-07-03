<?php

namespace App\Model;

class Command implements CommandInterface
{

    /**
     * @var array
     */
    private $commandAttribute;

    /**
     * @return array
     */
    public function getCommandAttribute(): array
    {
        return $this->commandAttribute;
    }

    /**
     * @param array $commandAttribute
     */
    public function setCommandAttribute(array $commandAttribute): void
    {
        $this->commandAttribute = $commandAttribute;
    }

    /**
     * @return array
     */
    public function getCommand(): array
    {
        return $this->commandAttribute;
    }
}