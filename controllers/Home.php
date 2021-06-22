<?php

require_once 'controllers/BaseController.php';

class Home extends BaseController
{

    public function index()
    {
        if(parent::hasOngoingSession()) {
            $this->view->username = $this->name;
            $this->setHeaderType("header_logged");
        } else {
            $this->view->username = "Guest";
        }

        $this->view->render('views/index/index.phtml');   //views/controller_name/action_name
    }

    public function doAction($param)
    {
        $this->view->message = "Do action loaded" . $param;

        $this->view->render('views/index/index.phtml');
    }
}