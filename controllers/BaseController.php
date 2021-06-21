<?php

require_once 'views/BaseView.php';

class BaseController
{

    protected $name;
    protected $email;
    protected $userId;

    function __construct()
    {
        $this->view = new BaseView();
    }

    function requestSession() {
        session_start();
        if(!(isset($_SESSION['logged'])))
        {
            header("location:Login");
        }
        else
        {
            $this->name = $_SESSION['name'];
            $this->email = $_SESSION['email'];
            $this->userId = $_SESSION['user_id'];
        }
    }
}