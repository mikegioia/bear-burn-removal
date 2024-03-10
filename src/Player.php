<?php

namespace Magic;

class Player
{
    /**
     * @var Board
     */
    private Board $board;

    /**
     * @var Deck
     */
    private Deck $deck;

    /**
     * @var Game
     */
    private Game $game;

    /**
     * @var Graveyard
     */
    private Graveyard $graveyard;

    /**
     * @var Hand
     */
    private Hand $hand;

    /**
     * @var bool
     */
    private bool $isStartingPlayer;

    /**
     * @var int
     */
    private int $lifeTotal = 20;

    /**
     * @var int
     */
    private int $playerId;

    /**
     * @var int
     */
    private int $turnsTaken = 0;

    public function __construct(Deck $deck, Game $game, int $playerId)
    {
        $this->board = new Board();
        $this->deck = $deck;
        $this->game = $game;
        $this->graveyard = new Graveyard();
        $this->hand = new Hand();
        $this->isStartingPlayer = 1 === $playerId;
        $this->playerId = $playerId;

        // Draw 7 cards into the opening hand
        for ($i = 0; $i < 7; ++$i) {
            $this->hand->addCard($this->deck->draw());
        }
    }

    /**
     * @return Deck
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * @return Graveyard
     */
    public function getGraveyard(): Graveyard
    {
        return $this->graveyard;
    }

    /**
     * @return Hand
     */
    public function getHand(): Hand
    {
        return $this->hand;
    }

    /**
     * @return bool
     */
    public function getIsStartingPlayer(): bool
    {
        return $this->isStartingPlayer;
    }

    /**
     * @return int
     */
    public function getLifeTotal(): int
    {
        return $this->lifeTotal;
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return int
     */
    public function getTurnsTaken(): int
    {
        return $this->turnsTaken;
    }

    /**
     * Perform the best sequence of actions for the player's turn.
     *   1. If burn in hand and bear on board, target bear
     *   2. If removal in hand and bear on board, target bear
     *   3. If burn in hand and board is clear, target player
     *   4. If bear on board since last turn, attack player.
     */
    public function takeTurn(): void
    {
        $this->untap();
        $this->draw();
        $this->main();
        $this->combat();
        $this->end();

        print_r($this->hand);
        print_r($this->board);
    }

    /**
     * Logs a game action to the event log.
     *
     * @param  string $message
     */
    private function logAction(string $message): void
    {
        $this->game->logAction($this, $message);
    }

    /**
     * Untap step.
     */
    private function untap(): void
    {
        $this->board->untap();
    }

    /**
     * Draw step.
     */
    private function draw(): void
    {
        // Skip draw step if it's the starting player's first turn
        if (0 === $this->turnsTaken
            && $this->getIsStartingPlayer()
        ) {
            return;
        }

        $this->hand->addCard($this->deck->draw());
    }

    /**
     * Main phase.
     */
    private function main(): void
    {
        $this->playLand();
        $this->playCreatures();
        // remove any creatures
        // damage opponent or creatures
    }

    /**
     * Combat phase.
     */
    private function combat(): void
    {
        // attack with any creatures from previous turn
    }

    /**
     * End step.
     */
    private function end(): void
    {
        ++$this->turnsTaken;
    }

    /**
     * Tries to play a land if we have one in hand.
     */
    private function playLand(): void
    {
        if ($this->hand->hasLand()) {
            $this->hand->getLand()->play($this->board);
            $this->logAction('played a land');
        }
    }

    /**
     * Tries to play one or more creatures.
     */
    private function playCreatures(): void
    {
        if ($this->hand->hasCreature()) {
            $creature = $this->hand->getCreature();

            if ($this->board->hasManaAvailable($creature)) {
                $creature->play($this->board);
                $this->logAction('played a creature');

                // Try to play another one if we can
                $this->playCreatures();
            } else {
                // Don't have mana so put the card back in hand
                $this->hand->addCard($creature);
            }
        }
    }
}
