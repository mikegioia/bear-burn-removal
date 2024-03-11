<?php

namespace Magic\Cards;

use Magic\Card;
use Magic\Player;

class Bear extends Card
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Bear';
    }

    /**
     * @return int
     */
    public function getCastingCost(): int
    {
        return 2;
    }

    /**
     * @return int
     */
    public function getPower(): int
    {
        return 2;
    }

    /**
     * @return int
     */
    public function getToughness(): int
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
     * @param  Player  $player
     */
    public function play(Player $player): void
    {
        // Tap mana to play this card
        $player->getBoard()->tapLands($this);

        // Add this creature to the battlefield
        $player->getBoard()->addCreature($this);

        $player->logAction('played a creature');
    }
}
