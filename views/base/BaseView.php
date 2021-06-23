<?php


class BaseView
{
    public function __construct() {

    }

    public function render($viewName) {
        require ($viewName);
    }
}