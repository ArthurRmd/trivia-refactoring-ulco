<?php

namespace Ulco;

class GameMessagePrinter
{
    private function show($text): void
    {
        echo $text."\n";
    }

    public function correctAnswer(Player $player): void
    {
        $text = $player->getName()." now has ".$player->getPurse()." Gold Coins.";
        $this->show($text);
    }

}
