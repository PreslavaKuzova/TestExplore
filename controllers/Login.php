<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');

class Login extends BaseController
{

    const STUDENT = "student";
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
        $username = $_POST["teacher-email"];
        $password = $_POST["teacher-password"];
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
                            $_SESSION['id'] = $student->getUserId();
                            $_SESSION['email'] = $student->getEmail();
                        } else {
                            //TODO handle error message
                            $this->view->message = "No such student";
                        };
                        break;
                    case "teacher" :
                        break;
                }
            }
        } else {
            //TODO show error message
        }
    }

}