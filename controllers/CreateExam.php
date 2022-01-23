<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';
require_once('./models/Database.php');

class CreateExam extends BaseController
{
    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/create_exam.phtml');
        $this->databaseConnection = Database::getInstance();
    }

    public function index()
    {
        $this->render();
    }

    public function edit()
    {
        $this->render();
    }

    public function save()
    {
        $examName = $_POST["exam-name"];
        $accessCode = $_POST["access-code"];
        $examLevel = $_POST["exam-level"];
        $teacherId = $_SESSION["teacher_id"];
        $date = date("y-m-d");
        $questions = array();
        $questionIndex = 1;

        while (true) {
            if (isset($_POST["question-title-" . $questionIndex])) {
                $content = $_POST["question-title-" . $questionIndex];
                $answerIndex = 1;
                $answers = array();
                while (true) {
                    if (isset($_POST["answer-" . $questionIndex . "-" . $answerIndex])) {
                        $ans = $_POST["answer-" . $questionIndex . "-" . $answerIndex];
                        $cor = isset($_POST["correct-" . $questionIndex . "-" . $answerIndex]);
                        $answers[] = new Answer($answerIndex, $ans, $cor);
                        $answerIndex++;
                    } else {
                        break;
                    }
                }

                $questions[] = new Question($questionIndex, $content, "multiple", null, $answers);

                $questionIndex++;
            } else {
                break;
            }
        }

        $exam = new Exam(null, $examName, $accessCode, $date, $examLevel, $teacherId, $questions);
        $this->databaseConnection->addExamWithQuestions($exam);
        header('Location: /TeacherExams');
    }
}