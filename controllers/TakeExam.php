<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';

class TakeExam extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/take_exam.phtml');
        if (parent::hasOngoingSession()) { //TODO add student check
            $this->setHeaderType("header_logged_student");
        } else {
            header("Location:/Home");
        }
    }

    public function index()
    {
        $examId = -1; //$_POST["exam-id"]; //TODO Make a check if exists
        $exam = $this->getExam($examId);
        $this->view->examId = $examId;
        $this->view->examName = $exam->getName();
        $this->view->examDate = $exam->getDateOfCreation();
        $this->view->examQuestions = $this->generateQuestionsHTML($exam);
        $this->render();
    }

    public function submit()
    {
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        $examId = -1; //$_POST["exam-id"]; //TODO Make a check if exists
        $exam = $this->getExam($examId);
        
    }

    private function getExam($examId)
    {
        //TODO Replace with database call
        return new Exam("Test Exam of Mathematics", "", "2021-05-10", "5", "1", array(
            new Question("What is a*b?", "multiple", array(new Answer("Rectangle", true), new Answer("Square", false))),
            new Question("What is pi?", "multiple", array(new Answer("3.14", true), new Answer("22/7", false), new Answer("a circle", false), new Answer("yummmy", true))),
            new Question("Which is bigger: 2 on power of 3 or 3 on the power of 2?", "multiple", array(new Answer("2^3", false), new Answer("3 ^ 2", false), new Answer("yo mamma", true), new Answer("they are equal", false))),
            new Question("What is a*b?", "multiple", array(new Answer("Rectangle", true), new Answer("Square", false))),
            new Question("What is a*b?", "multiple", array(new Answer("Rectangle", true), new Answer("Square", false))),
            new Question("What is a*b?", "multiple", array(new Answer("Rectangle", true), new Answer("Square", false))),
            new Question("What is a*b?", "multiple", array(new Answer("Rectangle", true), new Answer("Square", false))),
        ));
    }

    private function getFileContent($filename)
    {
        $file = fopen($filename, "r") or die("Unable to open file!");
        $contentBlock = fread($file, filesize($filename));
        fclose($file);
        return $contentBlock;
    }

    private function generateQuestionsHTML($exam) {
        $questionBlock = $this->getFileContent("views/take_exam_question.html");
        $answerBlock = $this->getFileContent("views/take_exam_answer.html");
        $resultBlock = "";

        $questionIndex = 1;
        foreach ($exam->questions as $question) {
            $resultAnswerBlock = "";
            $answerIndex = 1;
            foreach ($question->answers as $answer) {
                $resultAnswerBlock = $resultAnswerBlock . $answerBlock;
                $resultAnswerBlock = str_replace("{AID}", $answerIndex, $resultAnswerBlock);
                $resultAnswerBlock = str_replace("{QID}", $questionIndex, $resultAnswerBlock);
                $resultAnswerBlock = str_replace("{ANS_VAL}", $answer->getContent(), $resultAnswerBlock);
                $answerIndex++;
            }

            $resultBlock = $resultBlock . $questionBlock;
            $resultBlock = str_replace("{QID}", $questionIndex, $resultBlock);
            $resultBlock = str_replace("{QUESTION}", $question->getQuestionContent(), $resultBlock);
            $resultBlock = str_replace("{ANSWERS}", $resultAnswerBlock, $resultBlock);
            $questionIndex++;
        }
        return $resultBlock;
    }
}