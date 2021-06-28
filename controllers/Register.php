<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');

class Register extends BaseController
{

    const EMAIL = "email";
    const PASSWORD = "password";
    const PASSWORD_REPEAT = "password-repeat";
    const FIRST_NAME = "first-name";
    const LAST_NAME = "last-name";
    const GRADE = "grade";
    private $databaseConnection;

    public function __construct()
    {
        parent::__construct('views/register.phtml');
        $this->databaseConnection = Database::getInstance();
    }

    public function index()
    {
        $this->showMessage("");
    }

    public function studentRegistration()
    {
        if (isset($_POST[self::EMAIL]) && isset($_POST[self::FIRST_NAME]) && isset($_POST[self::LAST_NAME])
            && isset($_POST[self::GRADE]) && $_POST[self::PASSWORD] && isset($_POST[self::PASSWORD_REPEAT])) {

            if (strcmp($_POST[self::PASSWORD], $_POST[self::PASSWORD_REPEAT]) == 0) {
                $student = $this->databaseConnection->addStudent($_POST[self::EMAIL], $_POST[self::FIRST_NAME], $_POST[self::LAST_NAME],
                    $_POST[self::PASSWORD], $_POST[self::GRADE]);
                if ($student != null) {
                    if ($this->initSession($student)) {
                        header("Location:/Home");
                    } else {
                        $this->showMessage("Something went wrong. We couldn't create an account. Please try again.");
                    }
                } else {
                    $this->showMessage("Something went wrong. We couldn't create an account. Please try again.");
                }
            } else {
                $this->showMessage("Passwords don't match.");
            }
        }
    }

    public function initSession($student)
    {
        if (!empty($_POST[self::EMAIL]) && !empty($_POST[self::PASSWORD]) && !empty($_POST[self::PASSWORD_REPEAT])) {
            $_SESSION['logged'] = $student->getEmail();
            $_SESSION['name'] = $student->getFirstName() . " " . $student->getLastName();
            $_SESSION['user_id'] = $student->getUserId();
            $_SESSION['student_id'] = $student->getStudentId();
            $_SESSION['email'] = $student->getEmail();
            $_SESSION['level'] = $student->getLevel();
            return true;
        }
        return false;
    }

    private function showMessage($message): void
    {
        $this->view->message = $message;
        $this->render();
    }
}