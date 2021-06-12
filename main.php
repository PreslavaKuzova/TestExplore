<?php
    require_once('./test_explore_db.php');

    $databaseConnection = new Database();

    $databaseConnection->addExam(NULL, 1, 10);

?>