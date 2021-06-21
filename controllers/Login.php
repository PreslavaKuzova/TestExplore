<?php

require_once 'controllers/BaseController.php';

class Login extends BaseController
{

    public function index()
    {
        $this->view->render('views/login/index.phtml');   //views/controller_name/action_name
    }

    public function teacherLogin() {

    }

    public function studentLogin() {

    }

}