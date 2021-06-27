<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');
require_once('./models/dto/Department.php');

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

    public function filterExams()
    {
        $this->view->exams = $this->databaseConnection->fetchAllFilteredExams($this->getAllSelectedSubjectFilters());
        $this->render();
    }

    private function getAllSelectedSubjectFilters(): array
    {
        $subjects = array();

        foreach (Department::getInstance()->getDepartments() as &$department) {
            if (isset($_POST[$department])) {
                $subjects[] = $department;
            }
        }

        return $subjects;
    }

    private function getAllSelectedSortByFilters(): array
    {
        $filters = array();

        return $filters;
    }

}