<?php
    require_once('./test_explore_db.php');

    $databaseConnection = new Database();

    $questions = $databaseConnection->fetchAllExamQuestions(1);

    foreach ($questions as &$value) {
        echo $value->getQuestionType();
    }

?>