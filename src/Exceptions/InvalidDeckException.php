<?php

namespace Magic\Exceptions;

class InvalidDeckException extends \Exception
{
    public function __construct(int $size, int $minSize)
    {
        $this->message = sprintf(
            'Deck contained only %s cards, must have at least %s.',
            $size,
            $minSize
        );
    }
}
