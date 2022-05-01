<?php

use Ulco\Game;
use Ulco\GameMessagePrinter;

require_once(__DIR__.'/../vendor/autoload.php');

$messagePrinter = new GameMessagePrinter();
$game = new Game($messagePrinter);

$game->addPlayer("Chet")
    ->addPlayer("Pat")
    ->addPlayer("Sue");

$gameIsNotFinished = true;

while ($gameIsNotFinished) {
    $game->roll();
    $gameIsNotFinished = $game->runRound();
}


