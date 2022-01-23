<?php
class Exam
{
    //FIXME The fields are public in order for the json_encode to be able to read them.
    //It can be implemented in another way but much more complicated
    public $id;
    public $name;
    public $accessCode;
    public $dateOfCreation;
    public $level;
    public $teacher;
    public $questions;

    function __construct($id, $name, $accessCode, $dateOfCreation, $level, $teacher, $questions)
    {
        $this->id = $id;
        $this->name = $name;
        $this->accessCode = $accessCode;
        $this->dateOfCreation = $dateOfCreation;
        $this->level = $level;
        $this->teacher = $teacher;
        $this->questions = $questions;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
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

    function getName()
    {
        return $this->name;
    }
}
