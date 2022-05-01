<?php

namespace Ulco;

use PHPUnit\Util\Exception;

class QuestionsManager
{

    private array $listQuestions;


    public function __construct()
    {
        $this->listQuestions = [];
    }

    /**
     * @param  string  $category
     * @return $this
     */
    public function addQuestion(string $category): static
    {
        $this->listQuestions[] = new Questions($category, 50);
        return $this;
    }

    /**
     * @param  string  $category
     * @return string
     */
    public function getByQuestionByCategory(string $category) :string
    {
        return $this->getQuestionByCategory($category)
            ->getLast();
    }

    /**
     * @param  string  $category
     * @return mixed
     */
    private function getQuestionByCategory(string $category): mixed
    {
        /** @var Questions $question */
        foreach ($this->listQuestions as $questions) {
            if ($questions->getCategory() === $category) {
                return $questions;
            }
        }

        throw new Exception("Question not implemented");
    }


}
