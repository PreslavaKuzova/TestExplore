<?php

require_once 'controllers/BaseController.php';

class CustomError extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view->message = "The controller doesn't exist!";
        $this->view->render('views/error/index.phtml');
    }
}