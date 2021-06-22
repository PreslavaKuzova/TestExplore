<?php
class Exam
{
    private $accessCode;
    private $dateOfCreation;
    private $level;
    private $teacher;
    private $questions;

    function __construct($accessCode, $dateOfCreation, $level, $teacher, $questions)
    {
        $this->accessCode = $accessCode;
        $this->dateOfCreation = $dateOfCreation;
        $this->level = $level;
        $this->teacher = $teacher;
        $this->questions = $questions;
    }

    function getAccessCode()
    {
        return $this->accessCode;
    }

    function getDateOfCreation()
    {
        return $this->dateOfCreation;
    }

    function getLevel()
    {
        return $this->level;
    }

    function getTeacher()
    {
        return $this->teacher;
    }

    function getQuestions()
    {
        return $this->questions;
    }
}
