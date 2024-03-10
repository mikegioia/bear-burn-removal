<?php

namespace Magic;

use Magic\Exceptions\DrawFromEmptyDeckException;
use Magic\Exceptions\GameOverException;
use Magic\Exceptions\PlayerWithZeroLifeException;

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
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * @return Deck
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
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
     * @return Player
     */
    public function getOpponent(): Player
    {
        return $this->getGame()->getOpponent($this);
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
        try {
            $this->untap();
            $this->draw();
            $this->main();
            $this->combat();
            $this->end();
        } catch (DrawFromEmptyDeckException|PlayerWithZeroLifeException $e) {
            throw new GameOverException($this, $e->getMessage());
        }
    }

    /**
     * Decrease the player's life total. If it goes to 0 or lower,
     * throw an exception that the player lost.
     *
     * @param  int $amount
     */
    public function loseLife(int $amount): void
    {
        $this->lifeTotal = $this->lifeTotal - $amount;

        if ($this->lifeTotal <= 0) {
            throw new PlayerWithZeroLifeException($this);
        }
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
        $this->playRemoval();
        $this->playBurn();
    }

    /**
     * Combat phase.
     */
    private function combat(): void
    {
        // Attack with as many creatures as possible
        if ($this->board->hasUntappedCreatures()) {
            $creatures = $this->board->getUntappedCreatures();
            $count = 0;
            $damage = 0;

            foreach ($creatures as $creature) {
                if (! $creature->getIsSummoningSick()) {
                    $creature->tap();
                    ++$count;
                    $damage += $creature->getPower();
                }
            }

            if ($count) {
                $this->logAction(sprintf('attacked with %s creature%s dealing %s damage',
                    $count,
                    1 === $count ? '' : 's',
                    $damage
                ));

                $this->getOpponent()->loseLife($damage);
            }
        }
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
            $this->hand->takeLand()->play($this);
            $this->logAction('played a land');
        }
    }

    /**
     * Tries to play one or more creatures.
     */
    private function playCreatures(): void
    {
        if ($this->hand->hasCreature()) {
            $creature = $this->hand->takeCreature();

            if ($this->board->hasManaAvailable($creature)) {
                $creature->play($this);
                $this->logAction('played a creature');

                // Try to play another one if we can
                $this->playCreatures();
            } else {
                // Don't have mana so put the card back in hand
                $this->hand->addCard($creature);
            }
        }
    }

    /**
     * Tries to remove as many creatures as possible.
     */
    private function playRemoval(): void
    {
        if ($this->hand->hasRemoval()) {
            if ($this->getOpponent()->getBoard()->hasCreatures()) {
                $removal = $this->hand->takeRemoval();

                if ($this->board->hasManaAvailable($removal)) {
                    $removal->play($this);
                    $this->logAction('played a removal spell');

                    // Try to play another one if we can
                    $this->playRemoval();
                } else {
                    // Don't have mana so put the card back in hand
                    $this->hand->addCard($removal);
                }
            }
        }
    }

    /**
     * Tries to remove creatures and also win the game with
     * direct damage to the opponent.
     */
    private function playBurn(): void
    {
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
}
