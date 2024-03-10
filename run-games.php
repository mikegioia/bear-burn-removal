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

// Player win counters
$wins = [
    1 => 0,
    2 => 0
];

try {
    for ($i = 0; $i < 1000; $i++) {
        // Set up the new game
        $deckOne = new Deck(bear: 37, land: 23);
        $deckTwo = new Deck(removal: 37, land: 23);
        $game = new Game($deckOne, $deckTwo);

        // Play a game
        $winner = $game->play();
        $wins[$winner->getPlayerId()]++;
        // echo implode(PHP_EOL, $game->getPrintableGameLog());
    }

} catch (Throwable $e) {
    echo 'Error encountered during game!', PHP_EOL;
    echo $e->getMessage(), PHP_EOL;

    exit(1);
}

echo PHP_EOL, 'Results: ', PHP_EOL, '  Player 1: ', $wins[1],
    PHP_EOL, '  Player 2: ', $wins[2], PHP_EOL;
