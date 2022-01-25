<?php

class Department
{
    const LITERATURE = "literature";
    const MATHEMATICS = "mathematics";
    const GEOGRAPHY = "geography";
    const HISTORY = "history";
    const SCIENCE = "science";

    private $departments;

    private static $instance = null;

    private function __construct()
    {
        $this->departments = [self::LITERATURE, self::MATHEMATICS, self::GEOGRAPHY, self::HISTORY, self::SCIENCE];
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Department();
        }

        return self::$instance;
    }

    public function getDepartments()
    {
        return $this->departments;
    }
}