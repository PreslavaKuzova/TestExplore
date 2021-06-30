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
    }

    public function index()
    {
        $examId = $_POST["exam-id"];
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

        $examId = $_POST["exam-id"];
        $exam = $this->getExam($examId);
        
    }

    private function getExam($examId)
    {
        //TODO Replace with database call
        return new Exam("-1", "Test Exam of Mathematics", "", "2021-05-10", "5", "1", array(
            new Question("1", "Question 1: 3x−25y+4 is an example of an algebraic:", "multiple", array(new Answer("expression", true), new Answer("operation", false), new Answer("quotient", false),new Answer("equation", false))),
            new Question("2","Question 2: What is pi?", "multiple", array(new Answer("3.14", true), new Answer("22/6", false), new Answer("6.28", false), new Answer("0", false))),
            new Question("3","Question 3: Simplify: 3x^2+2x−7x^2", "multiple", array(new Answer("−2x^5", false), new Answer("10x^2−2x", false), new Answer("−4x^2+2x", true), new Answer("−2x^2", false))),
            new Question("4","Question 4: Solve this equation: 5−2(x+3)=13", "multiple", array(new Answer("x=−6", false), new Answer("x=−7", true), new Answer("x=−1", false), new Answer("x=4/3", false))),
            new Question("5","Question 5: x=−3 is a solution to which of these equations?", "multiple", array(new Answer("−6=−2x", false), new Answer("2x−1=x+7", false), new Answer("x+5=−2", false), new Answer("x^2+x−6=0", true))),
            new Question("6","Question 6: Solve the inequality: −2x+4>10", "multiple", array(new Answer("x<−3", true), new Answer("x≥−3", false), new Answer("x=−3", false), new Answer("x>−3", false))),
            new Question("7","Question 7: What is the coefficient in this expression: 3x2y", "multiple", array(new Answer("x", false), new Answer("3", true), new Answer("y", false), new Answer("2", false))),
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