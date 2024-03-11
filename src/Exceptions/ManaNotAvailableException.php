<?php

namespace Magic\Exceptions;

class ManaNotAvailableException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Mana is not available to play the requested card';
    }
}
