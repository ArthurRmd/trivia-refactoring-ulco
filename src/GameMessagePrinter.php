<?php

namespace Ulco;

class GameMessagePrinter implements IGameMessagePrinter
{
    /**
     * @param $text
     */
    private function show($text): void
    {
        echo $text."\n";
    }

    /**
     * @param  Player  $player
     */
    public function getGold(Player $player): void
    {
        $text = $player->getName()." now has ".$player->getPurse()." Gold Coins.";
        $this->show($text);
    }

    /**
     * @param  Player  $player
     */
    public function correctAnswer(Player $player): void
    {
        $this->show("Answer was correct!!!!");
        $this->getGold($player);

    }

    /**
     * @param  Player  $player
     */
    public function incorrectAnswer(Player $player): void
    {
        $this->show("Answer was corrent!!!!");
        $this->getGold($player);
    }

    /**
     * @param  string  $playerName
     * @param  int  $totalPlayer
     */
    public function playerAdded(string $playerName, int $totalPlayer): void
    {
        $this->show($playerName." was added");
        $this->show("They are player number ". $totalPlayer);
    }

    /**
     * @param  string  $question
     */
    public function questionName(string $question): void
    {
        $this->show($question);
    }

    /**
     * @param  Player  $player
     */
    public function wrongAnswer(Player $player): void
    {
        $this->show("Question was incorrectly answered");
        $this->show($player->getName()." was sent to the penalty box");
    }

    /**
     * @param  Player  $player
     */
    public function getNewLocation(Player $player): void
    {
        $text = $player->getName()."'s new location is ".$player->getPosition();
        $this->show($text);
    }

    /**
     * @param  string  $category
     */
    public function getCategory(string $category): void
    {
        $text = "The category is ".$category;
        $this->show($text);
    }

    /**
     * @param  Player  $player
     * @param  int  $roll
     */
    public function roleDice(Player $player, int $roll): void
    {
        $this->show($player->getName()." is the current player");
        $this->show("They have rolled a ".$roll);
    }

    /**
     * @param  Player  $player
     */
    public function gettingOutPenalty(Player $player): void
    {
        $this->show($player->getName()." is getting out of the penalty box");
    }

    /**
     * @param  Player  $player
     */
    public function notGettingOutPenalty(Player $player): void
    {
        $this->show($player->getName()." is not getting out of the penalty box");
    }
}
