<?php

require_once 'controllers/BaseController.php';

class Index extends BaseController
{

    public function index()
    {
//        echo "hellllllo";
        $this->view->message = "Hellooooo! This is Index!";
        $this->view->render('views/index/index.phtml');   //views/controller_name/action_name
    }
}