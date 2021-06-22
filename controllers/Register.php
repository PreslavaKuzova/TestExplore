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
        parent::__construct();
        $this->databaseConnection = new Database();
    }

    public function index()
    {
        $this->view->render('views/register/index.phtml');
    }

    public function studentRegistration()
    {
        if (isset($_POST[self::EMAIL]) && isset($_POST[self::FIRST_NAME]) && isset($_POST[self::LAST_NAME])
            && isset($_POST[self::GRADE]) && $_POST[self::PASSWORD] && isset($_POST[self::PASSWORD_REPEAT])) {

            if (strcmp($_POST[self::PASSWORD], $_POST[self::PASSWORD_REPEAT]) == 0) {
                $student = $this->databaseConnection->addStudent($_POST[self::EMAIL], $_POST[self::FIRST_NAME], $_POST[self::LAST_NAME],
                    $_POST[self::PASSWORD], $_POST[self::GRADE]);
                if ($student != null) {
                    //TODO redirect to the home screen and init a session
                } else {
                    //TODO show error message for creating user account
                }
            } else {
                //TODO error message to handle different passwords
            }
        }
    }


}