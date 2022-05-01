<?php

namespace Ulco;

class Game
{
    private const POSITION_CAN_MOVE_BACK = 11;
    private array $players;
    private GameMessagePrinter $messagePrinter;
    private QuestionsManager $questionsManager;


    public $currentPlayer = 0;
    public $isGettingOutOfPenaltyBox;

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

    function add($playerName): void
    {
        $this->players[] = new Player($playerName);
        $this->messagePrinter->playerAdded($playerName, count($this->players));
    }

    private function getCurrentPlayer(): Player
    {
        return $this->players[$this->currentPlayer];
    }


    function roll($roll)
    {
        $this->messagePrinter->roleDice($this->getCurrentPlayer(), $roll);
        $currentPlayer = $this->getCurrentPlayer();

        if ($currentPlayer->isInPenaltyBox()) {

            if ($roll % 2 !== 0) {
                $this->isGettingOutOfPenaltyBox = true;

                $this->messagePrinter->gettingOutPenalty($currentPlayer);
                $this->movePlayer($currentPlayer,$roll);
            } else {
                $this->messagePrinter->notGettingOutPenalty($currentPlayer);
                $this->isGettingOutOfPenaltyBox = false;
            }

            return;
        }

        $this->movePlayer($currentPlayer,$roll);

    }

    private function movePlayer(Player $player, int $roll)
    {
        $player->moveFoward($roll);
        if ($player->getPosition() > self::POSITION_CAN_MOVE_BACK) {
            $player->moveBack(12);
        }
        $this->messagePrinter->getNewLocation($player);
        $this->messagePrinter->getCategory($this->currentCategory());
        $this->askQuestion();

    }

    function askQuestion()
    {
        $questionName = $this->questionsManager
            ->getByCategory($this->currentCategory())
            ->getLast();

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
