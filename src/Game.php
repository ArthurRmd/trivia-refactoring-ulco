<?php

namespace Ulco;

function echoln($string)
{
    echo $string."\n";
}

class Game
{
    private array $players;
    private GameMessagePrinter $messagePrinter;
    var $popQuestions;
    var $scienceQuestions;
    var $sportsQuestions;
    var $rockQuestions;

    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;

    function __construct(GameMessagePrinter $messagePrinter)
    {

        $this->players = [];
        $this->messagePrinter = $messagePrinter;
        $this->popQuestions = [];
        $this->scienceQuestions = [];
        $this->sportsQuestions = [];
        $this->rockQuestions = [];

        for ($i = 0; $i < 50; $i++) {
            $this->popQuestions[] = "Pop Question ".$i;
            $this->scienceQuestions[] = "Science Question ".$i;
            $this->sportsQuestions[] = "Sports Question ".$i;
            $this->rockQuestions[] = "Rock Question ".$i;
        }
    }

    function add($playerName)
    {
        $this->players[] = new Player($playerName);
        echoln($playerName." was added");
        echoln("They are player number ".count($this->players));

        return true;
    }

    private function getCurrentPlayer() :Player
    {
        return $this->players[$this->currentPlayer];
    }


    function roll($roll)
    {
        echoln($this->getCurrentPlayer()->getName()." is the current player");
        echoln("They have rolled a ".$roll);

        if ($this->getCurrentPlayer()->isInPenaltyBox()) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;

                echoln($this->getCurrentPlayer()->getName()." is getting out of the penalty box");
                $this->getCurrentPlayer()->moveFoward($roll);
                if ($this->getCurrentPlayer()->getPosition() > 11) {
                    $this->getCurrentPlayer()->moveBack(12);
                }

                echoln($this->getCurrentPlayer()->getName()
                    ."'s new location is "
                    .$this->getCurrentPlayer()->getPosition());
                echoln("The category is ".$this->currentCategory());
                $this->askQuestion();
            } else {
                echoln($this->getCurrentPlayer()->getName()." is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }

        } else {

            $this->getCurrentPlayer()->moveFoward($roll);
            if ($this->getCurrentPlayer()->getPosition() > 11) {
                $this->getCurrentPlayer()->moveBack(12);
            }

            echoln($this->getCurrentPlayer()->getName()
                ."'s new location is "
                .$this->getCurrentPlayer()->getPosition());
            echoln("The category is ".$this->currentCategory());
            $this->askQuestion();
        }

    }

    function askQuestion()
    {
        if ($this->currentCategory() === "Pop") {
            echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() === "Science") {
            echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() === "Sports") {
            echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() === "Rock") {
            echoln(array_shift($this->rockQuestions));
        }
    }


    function currentCategory()
    {

        return match ($this->getCurrentPlayer()->getPosition()) {
            0, 4, 8 => 'Pop',
            1, 5, 9 => 'Science',
            2, 6, 10 => 'Sports',
            default => 'Rock'
        };
    }

    function wasCorrectlyAnswered()
    {
        if ($this->getCurrentPlayer()->isInPenaltyBox()) {

            if ($this->isGettingOutOfPenaltyBox) {
                echoln("Answer was correct!!!!");
                $this->getCurrentPlayer()->addPurse();
                $this->messagePrinter->correctAnswer($this->getCurrentPlayer());

                $winner = $this->didPlayerWin();
                $this->currentPlayer++;
                if ($this->currentPlayer == count($this->players)) {
                    $this->currentPlayer = 0;
                }

                return $winner;
            }

            $this->currentPlayer++;
            if ($this->currentPlayer == count($this->players)) {
                $this->currentPlayer = 0;
            }

            return true;
        }


        echoln("Answer was corrent!!!!");
        $this->getCurrentPlayer()->addPurse();
        $this->messagePrinter->correctAnswer($this->getCurrentPlayer());

        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }

        return $winner;
    }

    function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->getCurrentPlayer()->getName()." was sent to the penalty box");
        $this->getCurrentPlayer()->setIsInPenaltyBox(true);

        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }

        return true;
    }


    function didPlayerWin()
    {
        return !($this->getCurrentPlayer()->getPurse() == 6);
    }
}
