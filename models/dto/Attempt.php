<?php


class Attempt
{

    private $id;
    private $studentId;
    private $examId;
    private $timestamp;
    private $result;

    public function __construct($id, $studentId, $examId, $timestamp, $result)
    {
        $this->id = $id;
        $this->studentId = $studentId;
        $this->examId = $examId;
        $this->timestamp = $timestamp;
        $this->result = $result;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStudentId()
    {
        return $this->studentId;
    }

    public function getExamId()
    {
        return $this->examId;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getResult()
    {
        return $this->result;
    }


}