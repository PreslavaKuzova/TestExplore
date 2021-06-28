<?php
class Question
{
    //FIXME The fields are public in order for the json_encode to be able to read them.
    //It can be implemented in another way but much more complicated
    public $questionId;
    public $questionContent;
    public $questionType;
    public $answers;

    function __construct($questionId, $questionContent, $questionType, $answers)
    {
        $this->questionId = $questionId;
        $this->questionContent = $questionContent;
        $this->questionType = $questionType;
        $this->answers = $answers;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

    function getQuestionType()
    {
        return $this->questionType;
    }

    function getAnswers()
    {
        return $this->answers;
    }

    public function getQuestionContent()
    {
        return $this->questionContent;
    }
}
