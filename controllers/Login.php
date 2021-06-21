<?php

require_once 'controllers/BaseController.php';
require_once('./models/Database.php');

class Login extends BaseController
{

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
        $username = $_POST["student-email"];
        $password = $_POST["student-password"];
        $this->view->render('views/login/index.phtml');
    }

}