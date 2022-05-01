<?php

namespace Ulco;

class Game
{
    private const POSITION_CAN_MOVE_BACK = 11;
    private const NUMBER_POSITION_WHEN_MOVE_BACK = 12;
    private array $players;
    private IGameMessagePrinter $messagePrinter;
    private QuestionsManager $questionsManager;


    public $currentPlayer = 0;
    public $isGettingOutOfPenaltyBox;

    /**
     * @param  IGameMessagePrinter  $messagePrinter
     */
    public function __construct(IGameMessagePrinter $messagePrinter)
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

    /**
     * @param $playerName
     */
    public function addPlayer($playerName): void
    {
        $this->players[] = new Player($playerName);
        $this->messagePrinter
            ->playerAdded($playerName, count($this->players));
    }

    /**
     * @return Player
     */
    private function getCurrentPlayer(): Player
    {
        return $this->players[$this->currentPlayer];
    }


    public function roll($roll): void
    {
        $this->messagePrinter->roleDice($this->getCurrentPlayer(), $roll);
        $currentPlayer = $this->getCurrentPlayer();

        if (!$currentPlayer->isInPenaltyBox()) {
            $this->movePlayer($currentPlayer, $roll);

            return;
        }

        if ($roll % 2 !== 0) {
            $this->isGettingOutOfPenaltyBox = true;
            $this->messagePrinter->gettingOutPenalty($currentPlayer);
            $this->movePlayer($currentPlayer, $roll);

            return;
        }
        $this->messagePrinter->notGettingOutPenalty($currentPlayer);
        $this->isGettingOutOfPenaltyBox = false;


    }

    private function movePlayer(Player $player, int $roll)
    {
        $player->moveFroward($roll);
        if ($player->getPosition() > self::POSITION_CAN_MOVE_BACK) {
            $player->moveBack(self::NUMBER_POSITION_WHEN_MOVE_BACK);
        }
        $this->messagePrinter->getNewLocation($player);

        $currentCategory = $this->currentCategory();
        $this->messagePrinter->getCategory($currentCategory);
        $question = $this->questionsManager->getByQuestionByCategory($currentCategory);
        $this->messagePrinter->questionName($question);

    }

    private function currentCategory()
    {

        return match ($this->getCurrentPlayer()->getPosition()) {
            0, 4, 8 => 'Pop',
            1, 5, 9 => 'Science',
            2, 6, 10 => 'Sports',
            default => 'Rock'
        };
    }

    private function nextRound()
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }
    }

    function wasCorrectlyAnswered()
    {
        if ($this->getCurrentPlayer()->isInPenaltyBox()) {

            if ($this->isGettingOutOfPenaltyBox) {
                $this->getCurrentPlayer()->addPurse();
                $this->messagePrinter->correctAnswer($this->getCurrentPlayer());

                $winner = $this->didPlayerWin();
                $this->nextRound();

                return $winner;
            }

            $this->nextRound();

            return true;
        }


        $this->getCurrentPlayer()->addPurse();
        $this->messagePrinter->incorrectAnswer($this->getCurrentPlayer());

        $winner = $this->didPlayerWin();
        $this->nextRound();

        return $winner;
    }

    function wrongAnswer()
    {
        $this->messagePrinter->wrongAnswer($this->getCurrentPlayer());
        $this->getCurrentPlayer()->setIsInPenaltyBox(true);
        $this->nextRound();
        return true;
    }


    function didPlayerWin()
    {
        return !($this->getCurrentPlayer()->getPurse() == 6);
    }
}
