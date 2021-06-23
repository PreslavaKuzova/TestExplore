<?php

require_once 'controllers/BaseController.php';

class CustomError extends BaseController
{

    public function __construct()
    {
        parent::__construct('views/error.phtml');
    }

    public function index()
    {
        $this->view->message = "The controller doesn't exist!";
        $this->render();
    }
}