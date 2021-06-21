<?php
class Answer
{
    private $content;
    private $isCorrect;

    function __construct($content, $isCorrect)
    {
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