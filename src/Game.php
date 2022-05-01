<?php

namespace Ulco;

use PHPUnit\Util\Exception;

class Game
{
    private const POSITION_CAN_MOVE_BACK = 11;
    private const NUMBER_POSITION_WHEN_MOVE_BACK = 12;
    private const VALUE_QUESTION_IS_CORRECT = 7;
    private const NUMBER_POSSIBILITY_QUESTION = 9;
    private const VALUE_PURSE_WHEN_PLAYER__NOT_WIN = 6;
    private const FIRST_POSITION_PLAYER = 0;

    private array $players;
    private IGameMessagePrinter $messagePrinter;
    private QuestionsManager $questionsManager;
    private Dice $dice;

    private int $currentPlayer = 0;
    private bool $isGettingOutOfPenaltyBox;

    /**
     * @param  IGameMessagePrinter  $messagePrinter
     */
    public function __construct(IGameMessagePrinter $messagePrinter)
    {
        $this->players = [];
        $this->messagePrinter = $messagePrinter;
        $this->dice = new Dice();
        $this->questionsManager = new QuestionsManager();
        $this->questionsManager
            ->addQuestion('Pop')
            ->addQuestion('Science')
            ->addQuestion('Sports')
            ->addQuestion('Rock');
    }


    /**
     * @param $playerName
     * @return $this
     */
    public function addPlayer($playerName): self
    {
        $this->players[] = new Player($playerName);
        $this->messagePrinter
            ->playerAdded($playerName, count($this->players));

        return $this;
    }

    /**
     * @return Player
     */
    private function getCurrentPlayer(): Player
    {
        if(isset($this->players[$this->currentPlayer])) {
            return $this->players[$this->currentPlayer];
        }
        throw new Exception('Player is not set');
    }

    public function roll(): void
    {
        $this->dice->roll();

        $this->messagePrinter->roleDice($this->getCurrentPlayer(), $this->dice->getValue());
        $currentPlayer = $this->getCurrentPlayer();

        if (!$currentPlayer->isInPenaltyBox()) {
            $this->movePlayer($currentPlayer, $this->dice->getValue());

            return;
        }

        if ($this->dice->valueIsEven()) {
            $this->isGettingOutOfPenaltyBox = true;
            $this->messagePrinter->gettingOutPenalty($currentPlayer);
            $this->movePlayer($currentPlayer, $this->dice->getValue());

            return;
        }
        $this->messagePrinter->notGettingOutPenalty($currentPlayer);
        $this->isGettingOutOfPenaltyBox = false;
    }

    /**
     * @param  Player  $player
     * @param  int  $roll
     */
    private function movePlayer(Player $player, int $roll): void
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

    /**
     * @return string
     */
    private function currentCategory(): string
    {

        return match ($this->getCurrentPlayer()->getPosition()) {
            0, 4, 8 => 'Pop',
            1, 5, 9 => 'Science',
            2, 6, 10 => 'Sports',
            default => 'Rock'
        };
    }

    private function nextRound(): void
    {
        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = self::FIRST_POSITION_PLAYER;
        }
    }

    public function wasCorrectlyAnswered(): bool
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

    public function wrongAnswer(): bool
    {
        $this->messagePrinter->wrongAnswer($this->getCurrentPlayer());
        $this->getCurrentPlayer()->setIsInPenaltyBox(true);
        $this->nextRound();

        return true;
    }


    private function didPlayerWin(): bool
    {
        return $this->getCurrentPlayer()->getPurse() !== self::VALUE_PURSE_WHEN_PLAYER__NOT_WIN;
    }

    public function runRound(): bool
    {
        if (rand(0, self::NUMBER_POSSIBILITY_QUESTION) === self::VALUE_QUESTION_IS_CORRECT) {
            return $this->wrongAnswer();
        }

        return $this->wasCorrectlyAnswered();
    }
}
