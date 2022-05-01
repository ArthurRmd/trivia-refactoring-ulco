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

    public function addQuestion(string $category)
    {
        $this->listQuestions[] = new Questions($category, 50);
        return $this;
    }

    public function getByCategory(string $category) :Questions
    {
        return $this->getQuestionByCategory($category);
    }

    private function getQuestionByCategory(string $category)
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
