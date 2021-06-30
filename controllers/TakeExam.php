<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Answer.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/Question.php';
require_once 'models/ExamResultCalculator.php';
require_once('./models/Database.php');

class TakeExam extends BaseController
{
    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/take_exam.phtml');
        $this->databaseConnection = Database::getInstance();
    }

    public function index()
    {
        $examId = $_POST["exam-id"];
        $examAccessCode = $_POST["exam-access-code"];
        $exam = $this->getExam($examId);

        if ($exam->accessCode != "" && $exam->accessCode != $examAccessCode) {
            header('Location: /StudentExams');
        } else {
            $this->view->examId = $examId;
            $this->view->examName = $exam->getName();
            $this->view->examDate = $exam->getDateOfCreation();
            $this->view->examQuestions = $this->generateQuestionsHTML($exam);
            $this->render();
        }
    }

    public function submit()
    {
        $examId = $_POST["exam-id"];
        $exam = $this->getExam($examId);
        $userScore = ExamResultCalculator::calculateResult($exam, $_POST);
        $attemptId = $this->databaseConnection->addAttempt($examId, $_SESSION['user_id'], $userScore);
        if($attemptId != -1) {
            header('Location: /AttemptDetails/index/' . $examId . '&' . $attemptId);
        } else {
            header('Location: /CustomError');
        }
    }

    private function getExam($examId)
    {
        return $this->databaseConnection->fetchExamById($examId);
    }

    private function getFileContent($filename)
    {
        $file = fopen($filename, "r") or die("Unable to open file!");
        $contentBlock = fread($file, filesize($filename));
        fclose($file);
        return $contentBlock;
    }

    private function generateQuestionsHTML($exam)
    {
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