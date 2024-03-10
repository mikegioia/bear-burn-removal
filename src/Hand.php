<?php

namespace Magic;

use Magic\Exceptions\CardNotFoundException;

class Hand
{
    /**
     * @var array<Card>
     */
    private array $cards;

    /**
     * @param array<Card> $cards
     */
    public function __construct(array $cards = [])
    {
        $this->cards = $cards;
    }

    /**
     * @param Card $card
     */
    public function addCard(Card $card): void
    {
        array_push($this->cards, $card);
    }

    /**
     * @return array<Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Removes a creature, if found, from the list of cards
     * and returns that card.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function getCreature(): Card
    {
        foreach ($this->cards as $i => $card) {
            if ($card->isCreature()) {
                // Remove this card from hand
                unset($this->cards[$i]);

                return $card;
            }
        }

        throw new CardNotFoundException();
    }

    /**
     * Removes a land, if found, from the list of cards
     * and returns that card.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function getLand(): Card
    {
        foreach ($this->cards as $i => $card) {
            if ($card->isLand()) {
                // Remove this card from hand
                unset($this->cards[$i]);

                return $card;
            }
        }

        throw new CardNotFoundException();
    }

    /**
     * @return bool
     */
    public function hasCreature(): bool
    {
        foreach ($this->cards as $card) {
            if ($card->isCreature()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasLand(): bool
    {
        foreach ($this->cards as $card) {
            if ($card->isLand()) {
                return true;
            }
        }

        return false;
    }
}
