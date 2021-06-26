<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');

class StudentExams extends BaseController
{

    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/student_exams.phtml');
        $this->databaseConnection = new Database();
    }

    public function index()
    {
        $this->view->exams = $this->databaseConnection->fetchAllExams();
        $this->render();
    }

}