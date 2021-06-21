<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');

class Login extends BaseController
{

    const STUDENT = "student";
    const TEACHER = "teacher";
    private $databaseConnection;

    public function __construct()
    {
        parent::__construct();
        $this->databaseConnection = new Database();
    }

    public function index()
    {
        $this->view->render('views/login/index.phtml');   //views/controller_name/action_name
        $this->databaseConnection = new Database();
    }

    public function teacherLogin()
    {
        $this->initSession("teacher-email", "teacher-password", self::TEACHER);
        $this->view->render('views/login/index.phtml');
    }

    public function studentLogin()
    {
        $this->initSession("student-email", "student-password", self::STUDENT);
        $this->view->render('views/login/index.phtml');
    }

    public function initSession($emailIdentifier, $passwordIdentifier, $userIdentifier)
    {
        if (isset($_POST[$emailIdentifier]) && isset($_POST[$passwordIdentifier])) {
            if (!empty($_POST[$emailIdentifier]) && !empty($_POST[$passwordIdentifier])) {
                $email = htmlentities($_POST[$emailIdentifier], ENT_QUOTES);
                $password = htmlentities($_POST[$passwordIdentifier], ENT_QUOTES);

                switch ($userIdentifier) {
                    case self::STUDENT :
                        $student = $this->databaseConnection->fetchStudent($email, $password);
                        if ($student != null) {
                            $_SESSION['logged'] = $email;
                            $_SESSION['name'] = $student->getFirstName() . " " . $student->getLastName();
                            $_SESSION['user_id'] = $student->getUserId();
                            $_SESSION['student_id'] = $student->getStudentId();
                            $_SESSION['email'] = $email;
                            $_SESSION['level'] = $student->getLevel();
                        } else {
                            //TODO handle error message
                            $this->view->message = "No such student";
                        };
                        break;
                    case self::TEACHER :
                        $teacher = $this->databaseConnection->fetchTeacher($email, $password);
                        if ($teacher != null) {
                            $_SESSION['logged'] = $email;
                            $_SESSION['name'] = $teacher->getFirstName() . " " . $teacher->getLastName();
                            $_SESSION['user_id'] = $teacher->getUserId();
                            $_SESSION['teacher_id'] = $teacher->getTeacherId();
                            $_SESSION['email'] = $email;
                            $_SESSION['department'] = $teacher->getDepartment();
                        } else {
                            //TODO handle error message
                            $this->view->message = "No such teacher";
                        };
                        break;
                }
            }
        } else {
            //TODO show error message
        }
    }

}