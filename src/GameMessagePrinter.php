<?php

namespace Ulco;

class GameMessagePrinter
{
    private function show($text): void
    {
        echo $text."\n";
    }

    public function getGold(Player $player): void
    {
        $text = $player->getName()." now has ".$player->getPurse()." Gold Coins.";
        $this->show($text);
    }

    public function correctAnswer(Player $player)
    {
        $this->show("Answer was correct!!!!");
        $this->getGold($player);

    }

    public function incorrectAnswer(Player $player)
    {
        $this->show("Answer was corrent!!!!");
        $this->getGold($player);
    }

    public function playerAdded(string $playerName, int $totalPlayer)
    {
        $this->show($playerName." was added");
        $this->show("They are player number ". $totalPlayer);
    }

    public function questionName(string $question)
    {
        $this->show($question);
    }

    public function wrongAnswer(Player $player)
    {
        $this->show("Question was incorrectly answered");
        $this->show($player->getName()." was sent to the penalty box");
    }

    public function getNewLocation(Player $player)
    {
        $text = $player->getName()."'s new location is ".$player->getPosition();
        $this->show($text);
    }

    public function getCategory(string $category)
    {
        $text = "The category is ".$category;
        $this->show($text);
    }

    public function roleDice(Player $player, int $roll)
    {
        $this->show($player->getName()." is the current player");
        $this->show("They have rolled a ".$roll);
    }

    public function gettingOutPenalty(Player $player)
    {
        $this->show($player->getName()." is getting out of the penalty box");
    }

    public function notGettingOutPenalty(Player $player)
    {
        $this->show($player->getName()." is not getting out of the penalty box");
    }
}
