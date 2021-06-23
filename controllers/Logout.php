<?php


class Logout
{

    public function index() {
        session_start();
        unset($_SESSION["logged"]);
        unset($_SESSION["user_id"]);
        unset($_SESSION["email"]);
        unset($_SESSION["name"]);
        //Student
        unset($_SESSION["level"]);
        unset($_SESSION["student_id"]);
        //Teacher
        unset($_SESSION["teacher_id"]);
        unset($_SESSION["department"]);
        header("Location:Home");
    }
}