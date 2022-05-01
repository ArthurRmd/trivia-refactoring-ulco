<?php

namespace Ulco;

use PHPUnit\Util\Exception;

class Game
{
    private array $players;
    private GameMessagePrinter $messagePrinter;
    private QuestionsManager $questionsManager;


    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;

    function __construct(GameMessagePrinter $messagePrinter)
    {

        $this->players = [];
        $this->messagePrinter = $messagePrinter;

        $this->questionsManager = new QuestionsManager();
        $this->questionsManager
            ->addQuestion('Pop')
            ->addQuestion('Science')
            ->addQuestion('Sports')
            ->addQuestion('Rock');

    }

    function add($playerName):void
    {
        $this->players[] = new Player($playerName);
        $this->messagePrinter->playerAdded($playerName, count($this->players));
    }

    private function getCurrentPlayer() :Player
    {
        return $this->players[$this->currentPlayer];
    }


    function roll($roll)
    {
        $this->messagePrinter->roleDice($this->getCurrentPlayer(), $roll);

        if ($this->getCurrentPlayer()->isInPenaltyBox()) {

            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;

                $this->messagePrinter->gettingOutPenalty($this->getCurrentPlayer());
                $this->getCurrentPlayer()->moveFoward($roll);
                if ($this->getCurrentPlayer()->getPosition() > 11) {
                    $this->getCurrentPlayer()->moveBack(12);
                }

                $this->messagePrinter->getNewLocation($this->getCurrentPlayer());
                $this->messagePrinter->getCategory($this->currentCategory());
                $this->askQuestion();
            } else {
                $this->messagePrinter->notGettingOutPenalty($this->getCurrentPlayer());
                $this->isGettingOutOfPenaltyBox = false;
            }

        } else {

            $this->getCurrentPlayer()->moveFoward($roll);
            if ($this->getCurrentPlayer()->getPosition() > 11) {
                $this->getCurrentPlayer()->moveBack(12);
            }

            $this->messagePrinter->getNewLocation($this->getCurrentPlayer());
            $this->messagePrinter->getCategory($this->currentCategory());
            $this->askQuestion();
        }

    }

    function askQuestion()
    {
        $questionName = match ($this->currentCategory()) {
            'Pop' => array_shift($this->popQuestions),
            'Science' => array_shift($this->scienceQuestions),
            'Sports' => array_shift($this->sportsQuestions),
            'Rock' => array_shift($this->rockQuestions),
            default => throw new Exception('Question not implemented')
        };

        $this->messagePrinter->questionName($questionName);
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


        $this->getCurrentPlayer()->addPurse();
        $this->messagePrinter->incorrectAnswer($this->getCurrentPlayer());

        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }

        return $winner;
    }

    function wrongAnswer()
    {
        $this->messagePrinter->wrongAnswer($this->getCurrentPlayer());
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
