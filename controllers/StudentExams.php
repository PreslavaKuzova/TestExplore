<?php

require_once 'controllers/BaseController.php';

class StudentExams extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/student_exams.phtml');
    }

    public function index()
    {
        $this->render();
    }

}