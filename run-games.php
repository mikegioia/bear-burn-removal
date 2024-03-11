<?php

/**
 * Decks contain a mixture of burn, removal, bear, or land
 * cards and each is configured as a number between 0 and 60.
 *
 * Sample syntax:
 *
 *  $> php run-games.php \
 *       --deck1={bear:37,land:23} \
 *       --deck2={removal:37,land:23} \
 *       --count=100k
 */

use Magic\Deck;
use Magic\Game;

// Load vendor libraries, check if autoload exists
if (! file_exists(__DIR__.'/vendor/autoload.php')) {
    echo 'Composer autoload file does not exist!', PHP_EOL;
    echo 'Please run `composer install` first.', PHP_EOL;

    exit(1);
}

require __DIR__.'/vendor/autoload.php';

// @todo - read in command line arguments - $argv
// Number of games to play
$iterations = 10000;

// Player win counters
$wins = [
    1 => 0,
    2 => 0
];

echo 'Simulating '.number_format($iterations).' games', PHP_EOL;

try {
    for ($i = 0; $i < $iterations; $i++) {
        // Set up the new game
        $deckOne = new Deck(bear: 37, land: 23);
        $deckTwo = new Deck(removal: 37, land: 23);
        $game = new Game($deckOne, $deckTwo);

        // Play a game
        $winner = $game->play();
        $wins[$winner->getPlayerId()]++;

        // if ($winner->getPlayerId() === 1) {
        //     echo implode(PHP_EOL, $game->getPrintableGameLog()), PHP_EOL;

        //     exit(0);
        // }
    }

} catch (Throwable $e) {
    echo 'Error encountered during game!', PHP_EOL;
    echo $e->getMessage(), PHP_EOL;

    exit(1);
}

$p1Winrate = number_format($wins[1] / $iterations * 100, 2);
$p2Winrate = number_format($wins[2] / $iterations * 100, 2);

echo 'Results: ', PHP_EOL,
    '  Player 1: ', $wins[1], ' (', $p1Winrate, '%)', PHP_EOL,
    '  Player 2: ', $wins[2], ' (', $p2Winrate, '%)', PHP_EOL;
