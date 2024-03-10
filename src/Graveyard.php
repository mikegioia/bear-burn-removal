<?php

namespace Magic;

class Graveyard
{
	/**
     * @var array<Card>
     */
    private array $cards;

    /**
     * @param Card $card
     */
    public function addCard(Card $card): void
    {
    	$this->cards[] = $card;
    }

    /**
     * @return array<Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}