<?php

namespace App\Model;

interface CommandInterface
{
    /**
     * @return array
     */
    public function getCommand(): array;
}