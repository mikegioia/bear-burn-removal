<?php

namespace Magic\Cards;

use Magic\Card;
use Magic\Player;

class Removal extends Card
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Removal';
    }

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
    public function isRemoval(): bool
    {
        return true;
    }

    /**
     * Destroy an opponent's creature.
     *
     * @param  Player  $player
     */
    public function play(Player $player): void
    {
        // Tap mana to play this card
        $player->getBoard()->tapLands($this);

        // Move opponent's creature to their graveyard
        $player->getOpponent()->getGraveyard()->addCard(
            $player->getOpponent()->getBoard()->takeCreature()
        );

        // Move this card to the graveyard
        $player->getGraveyard()->addCard($this);

        $player->logAction('played a removal spell');
    }
}
