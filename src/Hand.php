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
     * Removes a burn spell, if found, from the list of cards
     * and returns that card.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function takeBurn(): Card
    {
        return $this->take('isBurn');
    }

    /**
     * Removes a creature, if found, from the list of cards
     * and returns that card.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function takeCreature(): Card
    {
        return $this->take('isCreature');
    }

    /**
     * Removes a land, if found, from the list of cards
     * and returns that card.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function takeLand(): Card
    {
        return $this->take('isLand');
    }

    /**
     * Removes a removal spell, if found, from the list of cards
     * and returns that card.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function takeRemoval(): Card
    {
        return $this->take('isRemoval');
    }

    /**
     * @return bool
     */
    public function hasBurn(): bool
    {
        return $this->has('isBurn');
    }

    /**
     * @return bool
     */
    public function hasCreature(): bool
    {
        return $this->has('isCreature');
    }

    /**
     * @return bool
     */
    public function hasLand(): bool
    {
        return $this->has('isLand');
    }

    /**
     * @return bool
     */
    public function hasRemoval(): bool
    {
        return $this->has('isRemoval');
    }

    /**
     * Checks if the player's hand has a card of the chosen type.
     *
     * @param  string  $typeFn
     *
     * @return bool
     */
    private function has(string $typeFn): bool
    {
        foreach ($this->cards as $card) {
            if ($card->$typeFn()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Takes a card out of the hand.
     *
     * @param  string $typeFn
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    private function take(string $typeFn): Card
    {
        foreach ($this->cards as $i => $card) {
            if ($card->$typeFn()) {
                // Remove this card from hand
                unset($this->cards[$i]);

                return $card;
            }
        }

        throw new CardNotFoundException();
    }
}
