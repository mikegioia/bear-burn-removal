<?php

namespace Magic\Exceptions;

use Magic\Player;

class GameOverException extends \Exception
{
    /**
     * @var Player
     */
    private Player $player;

    public function __construct(Player $player, string $message)
    {
        $this->player = $player;
        $this->message = $message;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }
}
