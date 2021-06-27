<?php


class ExamResultCalculator
{
    private const POINTS_PER_QUESTION = 10.0;

    public static function calculateResult($exam, $attemptData)
    {
        $totalScore = 0;
        foreach ($exam->questions as $question) {
            $maxCorrectAnswers = 0;
            $userCorrectAnswers = 0;
            foreach ($question->answers as $answer) {
                if($answer->isCorrect()) {
                    $maxCorrectAnswers++;
                    if(isset($attemptData["answer-" . $question->questionId . "-" . $answer->id])) {
                        $userCorrectAnswers++;
                    }
                } else if(isset($attemptData["answer-" . $question->questionId . "-" . $answer->id])) {
                    $userCorrectAnswers--;
                }
            }

            $userCorrectAnswers = $userCorrectAnswers < 0 ? 0 : $userCorrectAnswers;
            $totalScore = $totalScore + (($userCorrectAnswers * self::POINTS_PER_QUESTION) / $maxCorrectAnswers);
        }
        return $totalScore;
    }
}