<?php

namespace Magic\Cards;

use Magic\Card;
use Magic\Player;

class Burn extends Card
{
    /**
     * @var int
     */
    public const DAMAGE = 3;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Burn';
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
        // Tap mana to play this card
        $player->getBoard()->tapLands($this);

        // Deal damage directly to opponent
        $player->logAction('played a burn spell dealing 3 damage to opponent');
        $player->getOpponent()->loseLife(self::DAMAGE);

        /** This code will target creatures first before the player
         * // Target creatures first
         * if ($player->getOpponent()->getBoard()->hasCreatures()) {
         * // Move opponent's creature to their graveyard
         * $player->getOpponent()->getGraveyard()->addCard(
         * $player->getOpponent()->getBoard()->takeCreature()
         * );
         * $player->logAction('played a burn spell targeting a creature');
         * } else {
         * // Deal damage directly to opponent
         * $player->logAction('played a burn spell dealing 3 damage to opponent');
         * $player->getOpponent()->loseLife(self::DAMAGE);
         * }.
         */
    }
}
