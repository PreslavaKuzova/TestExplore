<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';

class CreateExam extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/create_exam.phtml');
    }

    public function index()
    {
        $this->render();
    }

    public function edit()
    {
        $this->render();
    }

    public function save() {
        //TODO Remove this when done debugging
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        $examName = $_POST["exam-name"];
        $accessCode = $_POST["access-code"];
        $examLevel = $_POST["exam-level"];
        $teacherId = $_SESSION["teacher_id"];
        $date = date("y-m-d");
        $questions = array();
        $questionIndex = 1;

        while (true) {
            if(isset($_POST["question-title-" . $questionIndex])) {
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
                $questions[] = new Question($questionIndex, $content, "multiple", $answers);
                $questionIndex++;
            } else {
                break;
            }
        }

        $exam = new Exam(1, $examName, $accessCode, $date, $examLevel, $teacherId, $questions);

        //TODO Replace this with database insert
        echo json_encode($exam);
    }
}