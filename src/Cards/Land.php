<?php

namespace Magic\Cards;

use Magic\Card;
use Magic\Player;

class Land extends Card
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Land';
    }

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
