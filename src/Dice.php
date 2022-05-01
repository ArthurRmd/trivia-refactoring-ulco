<?php

namespace Ulco;

use JetBrains\PhpStorm\Pure;

class Dice
{
    private const DICE_START = 0;
    private const DICE_FINISH = 5;

    private int $value;

    public function roll(): void
    {
        $this->value = rand(self::DICE_START, self::DICE_FINISH) + 1;
    }

    /**
     * @return bool
     */
    #[Pure] public function valueIsEven(): bool
    {
        return $this->getValue() % 2 !== 0;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }


}
