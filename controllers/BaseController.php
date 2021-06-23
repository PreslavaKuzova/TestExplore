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
        $this->view->headerType = "components/header_guest.js";
        $this->requestSessionData();
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

    function setHeaderType($header) {
        $this->view->headerType = "components/" . $header . ".js";
    }

    function setViewBody($viewBody) {
        $this->view->viewBody = $viewBody;
    }

    function render() {
        $this->view->render("views/base/base_page.phtml");
    }
}