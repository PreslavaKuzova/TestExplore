<?php


class Logout
{

    public function index() {
        session_start();
        unset($_SESSION["logged"]);
        unset($_SESSION["user_id"]);
        unset($_SESSION["student_id"]);
        unset($_SESSION["email"]);
        unset($_SESSION["level"]);
        unset($_SESSION["name"]);
        header("Location:Home");
    }
}