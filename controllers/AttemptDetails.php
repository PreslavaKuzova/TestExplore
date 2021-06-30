<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';
require_once 'models/dto/Attempt.php';
require_once 'models/ExamResultCalculator.php';

class AttemptDetails extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/attempt_details.phtml');
    }

    public function index($params)
    {
        $examId = $params[0];
        $attemptId = $params[1];
        $exam = $this->getExam($examId);
        $attempt = $this->getAttempt($attemptId);

        $this->view->examDate = "";
        $this->view->student = "";
        $this->view->result = "";
        if ($this->userId != $exam->teacher && $this->userId != $attempt->getStudentId()) {
            $this->view->message = "Unauthorized access. You are not the teacher nor the student for this exam. You cannot view the results.";
        } else {
            $this->view->message = $exam->name;
            $this->view->examDate = $exam->getDateOfCreation();
            $this->view->student = "Borislava Bardarska"; //TODO Get user from db
            $this->view->result = $attempt->getResult();
        }

        $this->render();
    }

    private function getExam($examId)
    {
        //TODO Replace with database call
        return new Exam("-1", "Test Exam of Mathematics", "", "2021-05-10", "5", "1", array(
            new Question("1", "What is a*b?", "multiple", array(new Answer(1, "Rectangle", true), new Answer(2, "Square", false))),
            new Question("2", "What is pi?", "multiple", array(new Answer(3, "3.14", true), new Answer(4, "22/7", false), new Answer(5, "a circle", false), new Answer(6, "yummmy", true))),
            new Question("3", "Which is bigger: 2 on power of 3 or 3 on the power of 2?", "multiple", array(new Answer(7, "2^3", false), new Answer(8, "3 ^ 2", false), new Answer(9, "yo mamma", true), new Answer(10, "they are equal", false))),
            new Question("4", "What is a*b?", "multiple", array(new Answer(331, "Rectangle", true), new Answer(221, "Square", false))),
            new Question("5", "What is a*b?", "multiple", array(new Answer(332, "Rectangle", true), new Answer(222, "Square", false))),
            new Question("6", "What is a*b?", "multiple", array(new Answer(333, "Rectangle", true), new Answer(223, "Square", false))),
            new Question("7", "What is a*b?", "multiple", array(new Answer(334, "Rectangle", true), new Answer(224, "Square", false))),
        ));
    }

    private function getAttempt($attemptId)
    {
        //TODO replace with database call
        return new Attempt(1, 1, 1, 1, 15);
    }
}