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


}
