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
        parent::__construct('views/login.phtml');
        $this->databaseConnection = Database::getInstance();
    }

    public function index()
    {
        $this->showMessage("");
    }

    public function teacherLogin()
    {
        $this->login("teacher-email", "teacher-password", self::TEACHER);
    }

    public function studentLogin()
    {
        $this->login("student-email", "student-password", self::STUDENT);
    }

    private function login($emailId, $passId, $userId)
    {
        if ($this->initSession($emailId, $passId, $userId)) {
            header("Location:/Home");
        } else {
            $this->showMessage("Login failed. Check your credentials and try again");
        }
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
                            return true;
                        } else {
                            return false;
                        }
                    case self::TEACHER :
                        $teacher = $this->databaseConnection->fetchTeacherByEmailAndPassword($email, $password);
                        if ($teacher != null) {
                            $_SESSION['logged'] = $email;
                            $_SESSION['name'] = $teacher->getFirstName() . " " . $teacher->getLastName();
                            $_SESSION['user_id'] = $teacher->getUserId();
                            $_SESSION['teacher_id'] = $teacher->getTeacherId();
                            $_SESSION['email'] = $email;
                            $_SESSION['department'] = $teacher->getDepartment();
                            return true;
                        } else {
                            return false;
                        }
                }
            }
        }
        return false;
    }

    private function showMessage($message): void
    {
        $this->view->message = $message;
        $this->render();
    }

}