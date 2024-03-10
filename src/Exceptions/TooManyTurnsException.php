<?php

namespace Magic\Exceptions;

use Magic\Game;

class TooManyTurnsException extends \Exception
{
    public function __construct()
    {
        $this->message = sprintf(
            'Too many turns were taken in the game, there is a maximum of %s allowed.',
            Game::MAX_TURN_COUNT
        );
    }
}
