<?php

class Teacher extends User
{
    public $teacherId;
    public $department;

    public function __construct($userId, $teacherId, $email, $firstName, $lastName, $department)
    {
        parent::__construct($userId, $email, $firstName, $lastName);

        $this->teacherId = $teacherId;
        $this->department = $department;
    }

    public function getTeacherId()
    {
        return $this->teacherId;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function getFullName()
    {
        return parent::getFirstName() . " " . parent::getLastName();
    }

}