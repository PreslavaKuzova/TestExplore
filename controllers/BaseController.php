<?php

require_once 'views/base/BaseView.php';

class BaseController
{
    protected $name;
    protected $email;
    protected $userId;

    function __construct($viewBody)
    {
        $this->view = new BaseView();
        $this->setViewBody($viewBody);

        $this->requestSessionData();

        if ($this->hasOngoingSession()) {
            if ($this->isStudent()) {
                $this->setHeaderType("header_logged_student");
            } else if ($this->isTeacher()) {
                $this->setHeaderType("header_logged_teacher");
            }
        } else {
            $this->setHeaderType("header_guest");
        }
    }

    function requestSessionData()
    {
        session_start();
        if ((isset($_SESSION['logged']))) {
            $this->name = $_SESSION['name'];
            $this->email = $_SESSION['email'];
            $this->userId = $_SESSION['user_id'];
        }
    }

    function hasOngoingSession()
    {
        return isset($_SESSION['logged']);
    }

    function isStudent()
    {
        return isset($_SESSION['student_id']);
    }

    function isTeacher()
    {
        return isset($_SESSION['teacher_id']);
    }

    private function setHeaderType($header)
    {
        $this->view->headerType = "/components/" . $header . ".js";
    }

    function setViewBody($viewBody)
    {
        $this->view->viewBody = $viewBody;
    }

    function render()
    {
        $this->view->render("views/base/base_page.phtml");
    }
}