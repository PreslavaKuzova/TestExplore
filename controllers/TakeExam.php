<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';
require_once 'models/ExamResultCalculator.php';

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
        $userScore = ExamResultCalculator::calculateResult($exam, $_POST);
        //TODO Save attempt in database
        header('Location: /AttemptDetails/index/' . $examId . '&' . 123); //TODO set attemptId
    }

    private function getExam($examId)
    {
        //TODO Replace with database call
        return new Exam("-1", "Test Exam of Mathematics", "", "2021-05-10", "5", "1", array(
            new Question("1", "What is a*b?", "multiple", array(new Answer(1, "Rectangle", true), new Answer(2, "Square", false))),
            new Question("2","What is pi?", "multiple", array(new Answer(3, "3.14", true), new Answer(4, "22/7", false), new Answer(5, "a circle", false), new Answer(6, "yummmy", true))),
            new Question("3","Which is bigger: 2 on power of 3 or 3 on the power of 2?", "multiple", array(new Answer(7, "2^3", false), new Answer(8, "3 ^ 2", false), new Answer(9, "yo mamma", true), new Answer(10, "they are equal", false))),
            new Question("4","What is a*b?", "multiple", array(new Answer(331, "Rectangle", true), new Answer(221, "Square", false))),
            new Question("5","What is a*b?", "multiple", array(new Answer(332, "Rectangle", true), new Answer(222, "Square", false))),
            new Question("6","What is a*b?", "multiple", array(new Answer(333, "Rectangle", true), new Answer(223, "Square", false))),
            new Question("7","What is a*b?", "multiple", array(new Answer(334, "Rectangle", true), new Answer(224, "Square", false))),

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

        foreach ($exam->questions as $question) {
            $resultAnswerBlock = "";
            foreach ($question->answers as $answer) {
                $resultAnswerBlock = $resultAnswerBlock . $answerBlock;
                $resultAnswerBlock = str_replace("{AID}", $answer->id, $resultAnswerBlock);
                $resultAnswerBlock = str_replace("{QID}", $question->questionId, $resultAnswerBlock);
                $resultAnswerBlock = str_replace("{ANS_VAL}", $answer->getContent(), $resultAnswerBlock);
            }

            $resultBlock = $resultBlock . $questionBlock;
            $resultBlock = str_replace("{QID}", $question->questionId, $resultBlock);
            $resultBlock = str_replace("{QUESTION}", $question->getQuestionContent(), $resultBlock);
            $resultBlock = str_replace("{ANSWERS}", $resultAnswerBlock, $resultBlock);
        }
        return $resultBlock;
    }
}