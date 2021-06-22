<?php
    require_once('./Database.php');

    $databaseConnection = new Database();

    $questions = $databaseConnection->fetchAllExamQuestions(1);

    foreach ($questions as &$value) {
        echo $value->getQuestionType();
        foreach ($value->getAnswers() as &$answer) {
            echo $answer->getContent();
            echo $answer->isCorrect();
        }
    }

?>