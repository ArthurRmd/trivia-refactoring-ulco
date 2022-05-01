<?php

namespace Ulco;

class Player
{
    private string $name;
    private int $position;
    private int $purse;
    private bool $isInPenaltyBox;

    /**
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->position = 0;
        $this->purse = 0;
        $this->isInPenaltyBox = false;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getPurse(): int
    {
        return $this->purse;
    }

    /**
     * @return bool
     */
    public function isInPenaltyBox(): bool
    {
        return $this->isInPenaltyBox;
    }

    /**
     * @param  bool  $isInPenaltyBox
     */
    public function setIsInPenaltyBox(bool $isInPenaltyBox): void
    {
        $this->isInPenaltyBox = $isInPenaltyBox;
    }

    public function moveFoward($translation)
    {
        $this->position += $translation;
    }

    public function moveBack($translation)
    {
        $this->position -= $translation;
    }





}
