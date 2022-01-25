<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';
require_once 'models/dto/Attempt.php';
require_once 'models/ExamResultCalculator.php';
require_once('./models/Database.php');

class AttemptDetails extends BaseController
{
    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/attempt_details.phtml');
        $this->databaseConnection = Database::getInstance();
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

        if ($this->userId != $exam->teacher && $_SESSION['student_id'] != $attempt->getStudentId()) {

            $this->view->message = "Unauthorized access. You are not the teacher nor the student for this exam. You cannot view the results.";
        } else {
            $this->view->message = $exam->name;
            $this->view->examDate = $exam->getDateOfCreation();
            $this->view->student = $this->databaseConnection->fetchStudentByStudentId($_SESSION['student_id']);
            $this->view->result = $attempt->getResult();
        }

        $this->render();
    }

    private function getExam($examId)
    {
        return $this->databaseConnection->fetchExamById($examId);
    }

    private function getAttempt($attemptId)
    {
        return $this->databaseConnection->fetchAttempt($attemptId);
    }
}