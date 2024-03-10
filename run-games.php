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

// Player decks
$deckOne = new Deck(bear: 37, land: 23);
$deckTwo = new Deck(removal: 37, land: 23);

// Run game
$game = new Game($deckOne, $deckTwo);
$game->play();
