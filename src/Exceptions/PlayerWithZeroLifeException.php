<?php

namespace Magic\Exceptions;

use Magic\Player;

class PlayerWithZeroLifeException extends GameOverException
{
    public function __construct(Player $player)
    {
        parent::__construct($player, 'Player life total went to 0');
    }
}
