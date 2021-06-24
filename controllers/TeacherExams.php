<?php

require_once 'controllers/BaseController.php';
require_once 'models/dto/Exam.php';

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

    }

    public function downloadJson() {

    }

    function getUserExams()
    {
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