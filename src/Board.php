<?php

namespace Magic;

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

    /**
     * @return array<Card>
     */
    public function getLands(): array
    {
        return $this->lands;
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
     * @throws ManaNotAvailableException
     *
     * @return Card
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
     * @throws ManaNotAvailableException
     *
     * @param  Card   $card
     */
    public function tapLands(Card $card): void
    {
        for ($i = 0; $i < $card->getCastingCost(); $i++) {
            $this->getUntappedLand()->tap();
        }
    }
}
