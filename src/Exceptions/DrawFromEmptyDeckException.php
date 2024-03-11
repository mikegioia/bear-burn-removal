<?php

namespace Magic\Exceptions;

class DrawFromEmptyDeckException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Tried drawing a card from an empty deck';
    }
}
