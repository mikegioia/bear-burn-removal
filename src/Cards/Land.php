<?php

namespace Magic\Cards;

use Magic\Board;
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
     * @param  Board  $board [description]
     */
    public function play(Board $board): void
    {
        $board->addLand($this);
    }
}
