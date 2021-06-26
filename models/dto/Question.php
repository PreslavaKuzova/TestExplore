<?php
class Question
{
    //FIXME The fields are public in order for the json_encode to be able to read them.
    //It can be implemented in another way but much more complicated
    public $questionType;
    public $answers;

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
