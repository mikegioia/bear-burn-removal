<?php

namespace Magic\Cards;

use Magic\Player;
use Magic\Card;

class Land extends Card
{
    /**
     * @return int
     */
    public function getCastingCost(): int
    {
        return 0;
    }

    /**
     * @return bool
     */
    public function getIsSummoningSick(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isLand(): bool
    {
        return true;
    }

    /**
     * Add the land to the board.
     *
     * @param  Player  $player
     */
    public function play(Player $player): void
    {
        // Add this land to the battlfield
        $player->getBoard()->addLand($this);
    }
}
