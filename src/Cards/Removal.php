<?php

namespace Magic\Cards;

use Magic\Board;
use Magic\Card;

class Removal extends Card
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
    public function isInstant(): bool
    {
        return true;
    }

    public function play(Board $board): void
    {
        //
    }
}
