<?php

namespace Magic;

use Magic\Cards\Bear;
use Magic\Cards\Burn;
use Magic\Cards\Land;
use Magic\Cards\Removal;
use Magic\Exceptions\DrawFromEmptyDeckException;
use Magic\Exceptions\InvalidDeckException;

class Deck
{
    /**
     * @var array<Card>
     */
    private array $cards = [];

    private const MIN_DECK_SIZE = 40;

    /**
     * Initializes deck.
     *
     * @param int $bear
     * @param int $burn
     * @param int $removal
     * @param int $land
     *
     * @throws InvalidDeckException
     */
    public function __construct(
        int $bear = 0,
        int $burn = 0,
        int $removal = 0,
        int $land = 0
    ) {
        // Check minimum deck requirements
        if ($bear + $burn + $removal + $land < self::MIN_DECK_SIZE) {
            throw new InvalidDeckException(
                $bear + $burn + $removal + $land,
                self::MIN_DECK_SIZE
            );
        }

        // Load any bear cards
        for ($i = 0; $i < $bear; ++$i) {
            $this->cards[] = new Bear();
        }

        // Load any burn cards
        for ($i = 0; $i < $burn; ++$i) {
            $this->cards[] = new Burn();
        }

        // Load any removal cards
        for ($i = 0; $i < $removal; ++$i) {
            $this->cards[] = new Removal();
        }

        // Load any removal cards
        for ($i = 0; $i < $land; ++$i) {
            $this->cards[] = new Land();
        }

        $this->shuffle();
    }

    /**
     * @return array<Card>
     */
    public function shuffle(): array
    {
        shuffle($this->cards);

        return $this->cards;
    }

    /**
     * @return array<Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @return Card
     *
     * @throws DrawFromEmptyDeckException
     */
    public function draw(): Card
    {
        if (! count($this->cards)) {
            throw new DrawFromEmptyDeckException();
        }

        return array_pop($this->cards);
    }
}
