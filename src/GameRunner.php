<?php

use Ulco\Game;

require_once(__DIR__.'/../vendor/autoload.php');

$aGame = new Game(new \Ulco\GameMessagePrinter());

$aGame->add("Chet");
$aGame->add("Pat");
$aGame->add("Sue");


do {

    $aGame->roll(rand(0, 5) + 1);

    if (rand(0, 9) == 7) {
        $notAWinner = $aGame->wrongAnswer();
        continue;
    }

    $notAWinner = $aGame->wasCorrectlyAnswered();


} while ($notAWinner);
