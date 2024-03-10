<?php

namespace Magic;

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
        return array_filter($this->lands, function (Card $land) {
            return false === $land->getIsTapped();
        });
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
}
