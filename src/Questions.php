<?php

namespace Ulco;

class Questions
{
    private string $category;
    private array $questions;

    /**
     * @param  string  $category
     */
    public function __construct(string $category, int $numberQuestions)
    {
        $this->category = $category;
        $this->questions = [];

        for ($i = 0; $i < $numberQuestions; $i++) {
            $this->questions[] = $this->category." Question ".$i;
        }
    }

    /**
     * @return string
     */
    public function getLast():string
    {
        return array_shift($this->questions);
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }




}
