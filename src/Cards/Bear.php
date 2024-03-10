<?php

namespace Magic\Cards;

use Magic\Board;
use Magic\Card;

class Bear extends Card
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
    public function isCreature(): bool
    {
        return true;
    }

    /**
     * Add the creature to the board.
     *
     * @param  Board  $board [description]
     */
    public function play(Board $board): void
    {
        $board->addCreature($this);
    }
}
