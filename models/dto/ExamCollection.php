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

    public function getJSONEncode() {
        return json_encode(get_object_vars($this));
    }
}
