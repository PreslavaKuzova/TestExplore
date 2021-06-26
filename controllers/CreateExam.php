<?php

require_once 'controllers/BaseController.php';

class CreateExam extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/create_exam.phtml');
        if(parent::hasOngoingSession() && parent::isTeacher()) {
            $this->setHeaderType("header_logged");
        } else {
            header("Location:/Home");
        }
    }

    public function index()
    {
        $this->render();
    }

    public function edit()
    {
        $this->render();
    }

    public function save() {

    }
}