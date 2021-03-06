<?php
class Answer
{
    //FIXME The fields are public in order for the json_encode to be able to read them.
    //It can be implemented in another way but much more complicated
    public $id;
    public $content;
    public $isCorrect;

    function __construct($id, $content, $isCorrect)
    {
        $this->id = $id;
        $this->content = $content;
        $this->isCorrect = $isCorrect;
    }

    function getContent()
    {
        return $this->content;
    }

    function isCorrect()
    {
        return $this->isCorrect;
    }
}