<?php
    require_once('./Database.php');

    $databaseConnection = new Database();

    $questions = $databaseConnection->fetchAllExamQuestions(1);

    foreach ($questions as &$value) {
        echo $value->getQuestionType();
    }

?>