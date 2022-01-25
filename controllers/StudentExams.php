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
        $exams = $this->getAllFilteredBySubjectExams();
        $this->view->exams = $this->sortDependingOnSortByFilter($exams);
        $this->render();
    }

    private function getAllSelectedSubjectFilters()
    {
        $subjects = array();

        foreach (Department::getInstance()->getDepartments() as &$department) {
            if (isset($_POST[$department])) {
                $subjects[] = $department;
            }
        }

        return $subjects;
    }

    public function getAllFilteredBySubjectExams()
    {
        $filters = $this->getAllSelectedSubjectFilters();

        if (empty($filters)) {
            $exams = $this->databaseConnection->fetchAllExams();
        } else {
            $exams = $this->databaseConnection->fetchAllFilteredExams($filters);
        }
        return $exams;
    }

    private function sortDependingOnSortByFilter($exams)
    {
        if (isset($_POST['sort-control'])) {
            $choice = $_POST['sort-control'];
            if ($choice == 1) {
                //Date of creation [Oldest to Newest]
                usort($exams, function ($exam1, $exam2) {
                    return ($exam1->getDateOfCreation() > $exam2->getDateOfCreation());
                });
            } elseif ($choice == 2) {
                //Date of creation [Newest to Oldest]
                usort($exams, function ($exam1, $exam2) {
                    return ($exam1->getDateOfCreation() < $exam2->getDateOfCreation());
                });
            } elseif ($choice == 3) {
                //Teacher name
                usort($exams, function ($exam1, $exam2) {
                    return strcmp($exam1->getTeacher()->getFullName(), $exam2->getTeacher()->getFullName());
                });
            } elseif ($choice == 4) {
                //Level [Ascending order]
                usort($exams, function ($exam1, $exam2) {
                    return ($exam1->getLevel() > $exam2->getLevel());
                });
            } elseif ($choice == 5) {
                //Level [Descending order]
                usort($exams, function ($exam1, $exam2) {
                    return ($exam1->getLevel() < $exam2->getLevel());
                });
            }
        }
        return $exams;
    }
}