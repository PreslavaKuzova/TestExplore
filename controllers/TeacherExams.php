<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/ExamCollection.php';
require_once('./models/Database.php');

class TeacherExams extends BaseController
{

    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/teacher_exams.phtml');
        $this->databaseConnection = Database::getInstance();
    }

    public function index()
    {
        $this->view->message = "";
        $this->view->exams = $this->getTeacherExams();
        $this->render();
    }

    public function uploadJson()
    {
        $uploadOk = 1;
        $fileName = basename($_FILES["fileToUpload"]["name"]);
        $fileSize = basename($_FILES["fileToUpload"]["size"]);
        $filePath = $_FILES["fileToUpload"]["tmp_name"];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileSize > 5000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($fileType != "json") {
            echo "Sorry, only JSON files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            $myFile = fopen($filePath, "r") or die("Unable to open file!");
            $jsonContent = fread($myFile, filesize($filePath));
            fclose($myFile);

//            $exams = json_decode($jsonContent);
//            //TODO replace this with database insert
//            foreach ($exams->exams as $exam) {
//                echo $exam->name . " " . $exam->accessCode . "<br>";
//            }

            $exams = json_decode($jsonContent);
            foreach ($exams->exams as $exam) {
                $this->databaseConnection->addExamWithQuestions($exam);
            }
        }
    }

    public function downloadJson()
    {
        $examsJson = json_encode(new ExamCollection($this->getTeacherExams()));
        $fileName = "allExams" . date("Y-m-d-H-i-s") . ".json";
        header('Content-disposition: attachment; filename=' . $fileName);
        header('Content-type: application/json');
        echo $examsJson;
    }

    private function getTeacherExams(): array
    {
        return $this->databaseConnection->fetchAllTeacherExams($_SESSION['teacher_id']);
    }
}