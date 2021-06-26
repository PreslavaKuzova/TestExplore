<?php

use JetBrains\PhpStorm\Pure;

require_once 'controllers/BaseController.php';
require_once 'models/dto/Exam.php';
require_once 'models/dto/ExamCollection.php';

class TeacherExams extends BaseController
{

    public function __construct()
    {
        parent::__construct('views/teacher_exams.phtml');
        if(parent::hasOngoingSession() && parent::isTeacher()) {
            $this->setHeaderType("header_logged");
        } else {
            header("Location:/Home");
        }
    }

    public function index()
    {
        $this->view->message = "";
        $this->view->exams = $this->getUserExams();
        $this->render();
    }

    public function uploadJson() {
        $uploadOk = 1;
        $fileName = basename($_FILES["fileToUpload"]["name"]);
        $fileSize = basename($_FILES["fileToUpload"]["size"]);
        $filePath = $_FILES["fileToUpload"]["tmp_name"];
        $fileType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));

        if ($fileSize > 5000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if($fileType != "json") {
            echo "Sorry, only JSON files are allowed.";
            $uploadOk = 0;
        }

        if($uploadOk == 1) {
            $myFile = fopen($filePath, "r") or die("Unable to open file!");
            $jsonContent = fread($myFile,filesize($filePath));
            fclose($myFile);

            $exams = json_decode($jsonContent);
            //TODO replace this with database insert
            foreach ($exams->exams as $exam) {
                echo $exam->name . " " . $exam->accessCode . "<br>";
            }
        }
    }

    public function downloadJson() {
        $examsJson = json_encode(new ExamCollection($this->getUserExams()));
        $fileName = "allExams" . date("Y-m-d-H-i-s") . ".json";
        header('Content-disposition: attachment; filename=' . $fileName);
        header('Content-type: application/json');
        echo $examsJson;
    }

    function getUserExams(): array
    {
        //TODO replace this with database query
        return array(
            new Exam("Mathematics Exam #1", "RFRT", "2021-06-28 17:44", "5", "TeacherName", array(3)),
            new Exam("Physics Exam #1", "RFRT", "2021-06-28 17:44", "6", "TeacherName", array(2)),
            new Exam("Mathematics Exam #2", "RFRT", "2021-06-28 17:44", "2", "TeacherName", array(3)),
            new Exam("Mathematics Exam #3", "RFRT", "2021-06-28 17:44", "2", "TeacherName", array(6)),
            new Exam("Mathematics Exam #4", "RFRT", "2021-06-28 17:44", "1", "TeacherName", array(3, 4, 5)),
            new Exam("Mathematics Exam #5", "RFRT", "2021-06-28 17:44", "4", "TeacherName", array(7)),
            new Exam("Mathematics Exam #6", "RFRT", "2021-06-28 17:44", "5", "TeacherName", array(3)),
            new Exam("Mathematics Exam #7", "RFRT", "2021-06-28 17:44", "5", "TeacherName", array(6)),
            new Exam("Mathematics Exam #8", "RFRT", "2021-06-28 17:44", "7", "TeacherName", array(4)),
        );
    }
}