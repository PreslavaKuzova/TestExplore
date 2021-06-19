<?php

require_once 'views/BaseView.php';

class BaseController
{

    function __construct()
    {
        $this->view = new BaseView();
    }
}