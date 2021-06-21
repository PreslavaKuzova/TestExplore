<?php

require_once 'controllers/BaseController.php';

class Home extends BaseController
{
    public function index()
    {
        $this->view->message = "Hellooooo! This is Index!";

        $this->view->render('views/index/index.phtml');   //views/controller_name/action_name
    }

    public function doAction($param)
    {
        $this->view->message = "Do action loaded" . $param;

        $this->view->render('views/index/index.phtml');
    }
}