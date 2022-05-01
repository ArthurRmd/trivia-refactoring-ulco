<?php

namespace Ulco;

interface IGameMessagePrinter
{
    /**
     * @param  Player  $player
     */
    public function getGold(Player $player): void;

    /**
     * @param  Player  $player
     */
    public function correctAnswer(Player $player): void;

    /**
     * @param  Player  $player
     */
    public function incorrectAnswer(Player $player): void;

    /**
     * @param  string  $playerName
     * @param  int  $totalPlayer
     */
    public function playerAdded(string $playerName, int $totalPlayer): void;

    /**
     * @param  string  $question
     */
    public function questionName(string $question): void;

    /**
     * @param  Player  $player
     */
    public function wrongAnswer(Player $player): void;

    /**
     * @param  Player  $player
     */
    public function getNewLocation(Player $player): void;

    /**
     * @param  string  $category
     */
    public function getCategory(string $category): void;

    /**
     * @param  Player  $player
     * @param  int  $roll
     */
    public function roleDice(Player $player, int $roll): void;

    /**
     * @param  Player  $player
     */
    public function gettingOutPenalty(Player $player): void;

    /**
     * @param  Player  $player
     */
    public function notGettingOutPenalty(Player $player): void;
}
