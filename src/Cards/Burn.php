<?php

namespace Magic\Cards;

use Magic\Card;
use Magic\Player;

class Burn extends Card
{
    /**
     * @return int
     */
    public function getCastingCost(): int
    {
        return 2;
    }

    /**
     * @return bool
     */
    public function isBurn(): bool
    {
        return true;
    }

    /**
     * Deal 3 damage to a creature or player.
     *
     * @param  Player $player
     */
    public function play(Player $player): void
    {
    }
}
