<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');

class StudentExams extends BaseController
{

    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/student_exams.phtml');
        $this->databaseConnection = Database::getInstance();
    }

    public function index()
    {
        $this->view->exams = $this->databaseConnection->fetchAllExams();
        $this->render();
    }

    public function filterExams() {
        if (isset($_POST['mathematics'])) {
            echo $_POST['mathematics'];
        }
        if(isset($_POST['sort-control'])) {
            echo $_POST['sort-control'];
        }
    }

}