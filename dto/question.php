<?php
class Question
{
    private $questionType;
    private $answers;

    function __construct($questionType, $answers)
    {
        $this->questionType = $questionType;
        $this->answers = $answers;
    }

    function getQuestionType()
    {
        return $this->questionType;
    }

    function getAnswers()
    {
        return $this->answers;
    }
}
