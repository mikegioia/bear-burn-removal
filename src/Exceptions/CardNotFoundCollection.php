<?php

namespace Magic\Exceptions;

class CardNotFoundException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Requested card does not exist in collection';
    }
}
