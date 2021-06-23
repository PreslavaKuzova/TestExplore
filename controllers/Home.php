<?php

require_once 'controllers/BaseController.php';

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/home.phtml');
    }

    public function index()
    {
        if(parent::hasOngoingSession()) {
            $this->view->username = $this->name;
            $this->setHeaderType("header_logged");
        } else {
            $this->view->username = "Guest";
        }

        $this->render();
    }

    public function doAction($param)
    {
        $this->view->message = "Do action loaded" . $param;
        $this->render();
    }
}