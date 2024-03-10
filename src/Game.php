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

use Magic\Exceptions\GameOverException;
use Magic\Exceptions\TooManyTurnsException;

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
     * Indexed by turn, then player ID.
     *
     * @var array<int, array<string, array<string>>>
     */
    private $gameLog = [];

    /**
     * @var int
     */
    private int $turns = 0;

    /**
     * @var int
     */
    public const MAX_TURN_COUNT = 250;

    /**
     * @param Deck $playerOneDeck
     * @param Deck $playerTwoDeck
     */
    public function __construct(Deck $playerOneDeck, Deck $playerTwoDeck)
    {
        $this->playerOne = new Player($playerOneDeck, $this, 1);
        $this->playerTwo = new Player($playerTwoDeck, $this, 2);
    }

    /**
     * Runs a single game from start to finish.
     * Returns the player who won.
     *
     * @return Player
     */
    public function play(): Player
    {
        try {
            // Each player takes turns until the game ends.
            // The game ends when an exception is thrown that
            // a player lost.
            while ($this->turns < self::MAX_TURN_COUNT) {
                $this->takeTurn();
            }
        } catch (GameOverException $e) {
            $this->logAction($e->getPlayer(), 'won the game, '.$e->getMessage());

            return $e->getPlayer();
        }

        throw new TooManyTurnsException();
    }

    /**
     * Update the turns counter and take a turn for each player.
     */
    public function takeTurn(): void
    {
        ++$this->turns;
        $this->gameLog[$this->turns] = [
            'P1' => [],
            'P2' => []
        ];

        $this->playerOne->takeTurn();
        $this->playerTwo->takeTurn();
    }

    /**
     * @return array<int, array<string, array<string>>>
     */
    public function getGameLog(): array
    {
        return $this->gameLog;
    }

    /**
     * @return array<string>
     */
    public function getPrintableGameLog(): array
    {
        $rows = [];

        foreach ($this->gameLog as $turnNumber => $playerEvents) {
            foreach ($playerEvents as $playerId => $events) {
                $rows[] = sprintf('Turn %s - %s - %s',
                    $turnNumber,
                    $playerId,
                    implode('; ', $events)
                );
            }
        }

        return $rows;
    }

    /**
     * Called from a player to get that player's opponent.
     *
     * @param  Player $player
     *
     * @return Player
     */
    public function getOpponent(Player $player): Player
    {
        return 1 === $player->getPlayerId()
            ? $this->playerTwo
            : $this->playerOne;
    }

    /**
     * Logs a player action message to the event log.
     *
     * @param  Player $player
     * @param  string $message
     */
    public function logAction(Player $player, string $message): void
    {
        $this->gameLog[$this->turns]['P'.$player->getPlayerId()][] = $message;
    }
}
