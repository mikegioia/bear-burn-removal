<?php

/**
 * Game is won when bears or burn spells deal 20,
 * or if the bear or burn player decks themself.
 *
 * Games where burn plays removal are not interesting,
 * but burn vs. bears and removal vs. bears is.
 * Also, it can be interesting to see what various
 * mixtures of card types have the highest win rate.
 *
 * Removal will always be played as soon as possible,
 * but is instant speed and will be played if mana is
 * open on the opponent's turn to prevent damage.
 *
 * Burn is held up as removal as a priority, and if
 * there are no creatures, sent at the player.
 */

namespace Magic;

class Game
{
    /**
     * @var Player
     */
    private Player $playerOne;

    /**
     * @var Player
     */
    private Player $playerTwo;

    /**
     * @var int
     */
    private int $turns = 0;

    public function __construct(Deck $playerOneDeck, Deck $playerTwoDeck)
    {
        $this->playerOne = new Player($playerOneDeck, 1);
        $this->playerTwo = new Player($playerTwoDeck, 2);
    }

    /**
     * Runs a single game from start to finish.
     * Returns the player who won.
     */
    public function play(): void
    {
        // Each player takes turns until the game ends
        $this->takeTurn();

        // Return winning player
    }

    public function takeTurn(): void
    {
        $this->turns++;

        $this->playerOne->takeTurn();
        $this->playerTwo->takeTurn();
    }
}
