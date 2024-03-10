<?php

namespace Magic;

use Magic\Exceptions\CardNotFoundException;
use Magic\Exceptions\ManaNotAvailableException;

class Board
{
    /**
     * @var array<Card>
     */
    private array $creatures = [];

    /**
     * @var array<Card>
     */
    private array $lands = [];

    /**
     * Untap all lands and creatures.
     */
    public function untap(): void
    {
        foreach ($this->creatures as $creature) {
            $creature->untap();
        }

        foreach ($this->lands as $land) {
            $land->untap();
        }
    }

    /**
     * @param Card $creature
     */
    public function addCreature(Card $creature): void
    {
        $this->creatures[] = $creature;
    }

    /**
     * @param Card $land
     */
    public function addLand(Card $land): void
    {
        $this->lands[] = $land;
    }

    /**
     * @return array<Card>
     */
    public function getCreatures(): array
    {
        return $this->creatures;
    }

    public function hasCreatures(): bool
    {
        return count($this->creatures) > 0;
    }

    /**
     * @return array<Card>
     */
    public function getUntappedCreatures(): array
    {
        return array_filter($this->creatures, function (Card $card) {
            return false === $card->getIsTapped();
        });
    }

    /**
     * @return bool
     */
    public function hasUntappedCreatures(): bool
    {
        return count($this->getUntappedCreatures()) > 0;
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
        foreach ($this->creatures as $i => $card) {
            // Remove this card from hand
            unset($this->creatures[$i]);

            return $card;
        }

        throw new CardNotFoundException();
    }

    /**
     * @return array<Card>
     */
    public function getLands(): array
    {
        return $this->lands;
    }

    /**
     * @return bool
     */
    public function hasLands(): bool
    {
        return count($this->lands) > 0;
    }

    /**
     * @return array<Card>
     */
    public function getUntappedLands(): array
    {
        return array_filter($this->lands, function (Card $card) {
            return false === $card->getIsTapped();
        });
    }

    /**
     * @return bool
     */
    public function hasUntappedLands(): bool
    {
        return count($this->getUntappedLands()) > 0;
    }


    /**
     * @return Card
     *
     * @throws ManaNotAvailableException
     */
    public function getUntappedLand(): Card
    {
        $untappedLands = $this->getUntappedLands();

        if (! count($untappedLands)) {
            throw new ManaNotAvailableException();
        }

        return array_pop($untappedLands);
    }

    /**
     * @param  Card  $card
     *
     * @return bool
     */
    public function hasManaAvailable(Card $card): bool
    {
        return count($this->getUntappedLands()) >= $card->getCastingCost();
    }

    /**
     * Taps lands to pay for the card.
     *
     * @param  Card   $card
     *
     * @throws ManaNotAvailableException
     */
    public function tapLands(Card $card): void
    {
        for ($i = 0; $i < $card->getCastingCost(); ++$i) {
            $this->getUntappedLand()->tap();
        }
    }
}
