<?php

namespace Magic\Exceptions;

class PlayerWithZeroLifeException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Player life total went to 0';
    }
}
