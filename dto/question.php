<?php
class Question
{
    private $questionType;
    private $answers;
    private $correctAnswer;

    function __construct($questionType, $answers, $correctAnswer)
    {
        $this->questionType = $questionType;
        $this->answers = $answers;
        $this->correctAnswer = $correctAnswer;
    }

    function getQuestionType()
    {
        return $this->questionType;
    }

    function getAnswers()
    {
        return $this->answers;
    }

    function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }
}
