<?php

require_once 'controllers/BaseController.php';

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/home.phtml');
        if(parent::hasOngoingSession()) {
            $this->view->username = $this->name;
        } else {
            $this->view->username = "Guest";
        }
    }

    public function index()
    {
        $this->render();
    }
}