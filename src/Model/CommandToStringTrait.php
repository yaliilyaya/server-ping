<?php

namespace App\Model;

/**
 * @method array getCommand()
 */
trait CommandToStringTrait
{
    public function __toString()
    {
        return implode(" ", $this->getCommand());
    }
}