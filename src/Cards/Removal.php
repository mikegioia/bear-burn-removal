<?php

namespace Magic\Cards;

use Magic\Player;
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
    }
}
