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
     * @param array<Card> $cards
     */
    public function setCards(array $cards = []): void
    {
        $this->cards = $cards;
    }

    /**
     * @return string
     */
    public function getCardList(): string
    {
        return implode(', ', array_map(function (Card $card) {
            return (string) $card;
        }, $this->cards));
    }

    /**
     * Draw the player's opening hand. If there is only one land
     * or only one spell, up to three mulligans are allowed.
     *
     * @param  Player  $player
     * @param  int     $putBack   Count of cards to put back after mulligan
     */
    public function drawOpening(Player $player, int $putBack = 0): void
    {
        // Draw seven into the hand
        for ($i = 0; $i < 7; ++$i) {
            $this->addCard($player->getDeck()->draw());
        }

        // Keep the hand if we have between 2 and 6 lands
        // or if we've mulliganed twice already
        $landCount = $this->countLand();

        if (($landCount > 1 && $landCount < 7) || $putBack >= 3) {
            if ($putBack) {
                $this->putBackMulliganCards($player->getDeck(), $putBack);
            }

            return;
        }

        // 0, 1, or 7 land hands are thrown back up to two times
        // Put the cards back in the deck
        foreach ($this->cards as $card) {
            $player->getDeck()->addCard($card);
        }

        // Reset the deck, empty the hand
        $player->getDeck()->shuffle();
        $this->setCards([]);

        $player->logAction('took mulligan');

        // Take a mulligan
        $this->drawOpening($player, $putBack + 1);
    }

    /**
     * @return int
     */
    public function countLand(): int
    {
        return $this->count('isLand');
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
     * Takes one of a nonland card in order of importance.
     *
     * @return Card
     *
     * @throws CardNotFoundException
     */
    public function takeNonland(): Card
    {
        if ($this->hasRemoval()) {
            return $this->takeRemoval();
        }

        if ($this->hasBurn()) {
            return $this->takeBurn();
        }

        if ($this->hasCreature()) {
            return $this->takeCreature();
        }

        throw new CardNotFoundException();
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
     * Counts cards of a certain type.
     *
     * @param  string $typeFn
     *
     * @return int
     */
    private function count(string $typeFn): int
    {
        return count(array_filter($this->cards, function (Card $card) use ($typeFn) {
            return $card->$typeFn();
        }));
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

    /**
     * Puts back cards into the deck after a mulligan based on
     * the composition of the hand.
     *
     * @param  Deck $deck
     * @param  int  $putBack
     */
    private function putBackMulliganCards(Deck $deck, int $putBack): void
    {
        for ($i = 0; $i < $putBack; ++$i) {
            $handSize = count($this->cards);
            $landRatio = $this->countLand() / $handSize;

            if ($landRatio >= 0.5) {
                $deck->addCard($this->takeLand());
            } else {
                $deck->addCard($this->takeNonland());
            }
        }
    }
}
