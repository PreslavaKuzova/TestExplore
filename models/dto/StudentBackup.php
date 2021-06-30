<?php


class Student extends User
{
    private $studentId;
    private $level;

    public function __construct($userId, $studentId, $email, $firstName, $lastName, $level)
    {
        parent::__construct($userId, $email, $firstName, $lastName);

        $this->studentId = $studentId;
        $this->level = $level;
    }

    public function getStudentId()
    {
        return $this->studentId;
    }

    public function getLevel()
    {
        return $this->level;
    }
}