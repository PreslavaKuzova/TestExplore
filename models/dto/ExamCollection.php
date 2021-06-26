<?php
class ExamCollection
{
    public array $exams = [];

    function __construct($exams)
    {
        $this->exams = $exams;
    }

    public function getExams(): array
    {
        return $this->exams;
    }
}
