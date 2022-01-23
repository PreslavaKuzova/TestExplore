<?php
require_once('./models/dto/Question.php');
require_once('./models/dto/User.php');
require_once('./models/dto/Student.php');
require_once('./models/dto/Teacher.php');
require_once('./models/dto/Exam.php');
require_once('./models/dto/Answer.php');

class Database
{
    const INVALID_ID = -1;
    const FIRST_NAME = "first_name";
    const LAST_NAME = "last_name";
    const EMAIL = "email";
    const USER_ID = "user_id";
    const LEVEL = "level";
    const STUDENT_ID = "student_id";
    const TEACHER_ID = "teacher_id";
    const DEPARTMENT = "department";

    private $dbtype;
    private $host;
    private $username;
    private $pass;
    private $dbname;
    private $connection;

    private static $instance = null;

    private function __construct($dbtype = "mysql", $host = "localhost", $username = "root", $pass = "", $dbname = "test_explore")
    {
        $this->dbtype = $dbtype;
        $this->host = $host;
        $this->username = $username;
        $this->pass = $pass;
        $this->dbname = $dbname;

        try {
            $this->connection = new PDO(
                "$dbtype:host=$host;dbname=$dbname",
                $username,
                $pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance(): ?Database
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    function addUser($email, $firstName, $lastName, $password)
    {
        $insertedId = self::INVALID_ID;
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO user(user_id, email, first_name, last_name, password) 
                        VALUES(:user_id, :email, :firstName, :lastName, :password)";
            $stmt = $this->connection->prepare($sql);
            if ($stmt->execute([
                ':user_id' => NULL, ':email' => $email, ':firstName' => $firstName, ':lastName' => $lastName, ':password' => $password
            ])) {
                $insertedId = $this->connection->lastInsertId();
            };

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
            return $insertedId;
        }
        return $insertedId;
    }

    function addStudent($email, $firstName, $lastName, $password, $level): ?Student
    {
        $insertedId = $this->addUser($email, $firstName, $lastName, $password);
        if ($insertedId != self::INVALID_ID) {
            try {
                $this->connection->beginTransaction();

                $sql = "INSERT INTO student(student_id, user_id, level) VALUES(:studentId, :userId, :level)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([':studentId' => NULL, ':userId' => $insertedId, ':level' => $level]);

                $this->connection->commit();
                return new Student($insertedId, $this->connection->lastInsertId(), $email, $firstName, $lastName, $level);
            } catch (PDOException $e) {
                $this->connection->rollBack();
                echo $e->getMessage();
                return null;
            }
        }
        return null;
    }

    function addTeacher($email, $firstName, $lastName, $password, $department): ?Teacher
    {
        $insertedId = $this->addUser($email, $firstName, $lastName, $password);
        if ($insertedId != self::INVALID_ID) {
            try {
                $this->connection->beginTransaction();

                $sql = "INSERT INTO teacher(teacher_id, user_id, department) VALUES(:teacherId, :userId, :department)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([':teacherId' => NULL, ':userId' => $insertedId, ':department' => $department]);

                $this->connection->commit();

                return new Teacher($insertedId, $this->connection->lastInsertId(), $email, $firstName, $lastName, $department);
            } catch (PDOException $e) {
                $this->connection->rollBack();
                echo $e->getMessage();
                return null;
            }
        }
        return null;
    }

    function addExamWithQuestions($exam)
    {
        $examId = $this->addExam($exam->accessCode, $exam->teacherId, $exam->level);
        if ($examId != self::INVALID_ID) {
            foreach ($exam->questions as $question) {
                $questionId = $this->addQuestion($question->questionContent, $question->questionType, $examId);
                if ($questionId != self::INVALID_ID) {
                    foreach ($question->answers as $answer) {
                        $this->addAnswer($answer->content, $answer->isCorrect, $questionId);
                    }
                }
            }
        }
    }

    private function addExam($accessCode, $teacherId, $examLevel)
    {
        $insertedId = self::INVALID_ID;
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO exam(exam_id, access_code, teacher_id, exam_level)
                        VALUES(:examId, :accessCode, :teacherId, :examLevel)";
            $stmt = $this->connection->prepare($sql);

            if ($stmt->execute([
                ':examId' => NULL, ':accessCode' => $accessCode,
                ':teacherId' => $teacherId, ':examLevel' => $examLevel
            ])) {
                $insertedId = $this->connection->lastInsertId();
            }
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }
        return $insertedId;
    }

    function addQuestion($questionContent, $questionType, $examId)
    {
        $insertedId = self::INVALID_ID;

        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO question(question_id, content, question_type, exam_id) 
                        VALUES(:questionId, :questionContent, :questionType, :examId)";
            $stmt = $this->connection->prepare($sql);

            if ($stmt->execute([
                ':questionId' => NULL, ':questionContent' => $questionContent, ':questionType' => $questionType, ':examId' => $examId
            ])) {
                $insertedId = $this->connection->lastInsertId();
            }

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $insertedId;
    }

    function addAnswer($content, $isCorrect, $questionId)
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO answer(answer_id, content, is_correct, question_id)  
                        VALUES(:answerId, :content, :isCorrect, :questionId)";

            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                ':answerId' => NULL, ':content' => $content, ':isCorrect' => $isCorrect, ':questionId' => $questionId]);

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }
    }

    function fetchAllExamQuestions($examId): array
    {
        $questions = array();

        try {
            $sql = "SELECT * FROM question WHERE exam_id=:examId";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":examId", $examId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $answers = $this->fetchAllQuestionsAnswers($row['question_id']);
                $questions[] = new Question($row['question_id'], $row['content'], $row['question_type'], $row['exam_id'],
                    $answers);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $questions;
    }

    function fetchAllQuestionsAnswers($questionId): array
    {
        $answers = array();

        try {
            $sql = "SELECT * FROM answer WHERE question_id=:questionId";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":questionId", $questionId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $answers[] = new Answer($row['answer_id'], $row['content'], $row['is_correct']);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $answers;
    }

    function fetchAllExams(): array
    {
        $exams = array();
        try {
            $sql = "SELECT * FROM exam";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $teacher = $this->fetchTeacherByTeacherId($row['teacher_id']);
                $questions = $this->fetchAllExamQuestions($row['exam_id']);
                $exams[] = new Exam($row['exam_id'], $teacher->getDepartment() . ' Exam',
                    $row['access_code'], $row['date_of_creation'], $row['exam_level'], $teacher, $questions);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $exams;
    }

    function fetchAllFilteredExams($subjectFilters): array
    {
        $filteredExams = array();

        foreach ($this->fetchAllExams() as &$exam) {
            foreach ($subjectFilters as &$subject) {
                if (strcmp($exam->getTeacher()->getDepartment(), $subject) == 0) {
                    $filteredExams[] = $exam;
                }
            }
        }

        return $filteredExams;
    }

    function fetchAllTeacherExams($teacherId): array
    {
        $exams = array();

        try {
            $sql = "SELECT * FROM exam WHERE teacher_id=:teacherId";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":teacherId", $teacherId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $questions = $this->fetchAllExamQuestions($row['exam_id']);
                $teacher = $this->fetchTeacherByTeacherId($row['teacher_id']);
                $exams[] = new Exam($row['exam_id'], $teacher->getDepartment() . " exam",
                    $row['access_code'], $row['date_of_creation'], $row['exam_level'], $teacher, $questions);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $exams;
    }

    private function fetchUser($email, $password): ?User
    {
        try {
            $sql = "SELECT * FROM user WHERE email=:email AND password=:password";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                return null;
            } else {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                return new User($user[self::USER_ID], $user[self::EMAIL], $user[self::FIRST_NAME], $user[self::LAST_NAME]);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return null;
    }

    private function fetchUserById($userId): ?User
    {
        try {
            $sql = "SELECT * FROM user WHERE user_id=:userId";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":userId", $userId);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                return null;
            } else {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                return new User($user[self::USER_ID], $user[self::EMAIL], $user[self::FIRST_NAME], $user[self::LAST_NAME]);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return null;
    }

    function fetchStudent($email, $password): ?Student
    {
        $user = $this->fetchUser($email, $password);
        return $this->fetchStudentFromUser($user);
    }

    /**
     * @param User|null $user
     * @return Student|null
     */
    public function fetchStudentFromUser(?User $user): ?Student
    {
        if ($user != null) {
            try {
                $userId = $user->getUserId();
                $sql = "SELECT * FROM student WHERE user_id=:userId";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindParam(":userId", $userId);
                $stmt->execute();

                if ($stmt->rowCount() != 1) {
                    return null;
                } else {
                    $student = $stmt->fetch(PDO::FETCH_ASSOC);
                    return new Student($user->getUserId(), $student[self::STUDENT_ID], $user->getEmail(),
                        $user->getFirstName(), $user->getLastName(), $student[self::LEVEL]);
                }
            } catch (PDOException $e) {
                $this->connection->rollBack();
                echo $e->getMessage();
            }
        }
        return null;
    }

    /**
     * @param User|null $user
     * @param $userId
     * @return Teacher|null
     */

    public function fetchTeacherFromUser(?User $user): ?Teacher
    {
        if ($user != null) {
            try {
                $userId = $user->getUserId();
                $sql = "SELECT * FROM teacher WHERE user_id=:userId";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindParam(":userId", $userId);
                $stmt->execute();

                if ($stmt->rowCount() != 1) {
                    return null;
                } else {
                    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
                    return new Teacher($user->getUserId(), $teacher[self::TEACHER_ID], $user->getEmail(),
                        $user->getFirstName(), $user->getLastName(), $teacher[self::DEPARTMENT]);
                }
            } catch (PDOException $e) {
                $this->connection->rollBack();
                echo $e->getMessage();
            }
        }
        return null;
    }

    private function fetchTeacherByTeacherId($teacherId): ?Teacher
    {
        try {
            $sql = "SELECT * FROM teacher WHERE teacher_id=:teacherId";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":teacherId", $teacherId);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                return null;
            } else {
                $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
                $user = $this->fetchUserById($teacher[self::USER_ID]);
                return new Teacher($user->getUserId(), $teacher[self::TEACHER_ID], $user->getEmail(),
                    $user->getFirstName(), $user->getLastName(), $teacher[self::DEPARTMENT]);
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return null;
    }

    function fetchTeacherByEmailAndPassword($email, $password): ?Teacher
    {
        $user = $this->fetchUser($email, $password);

        return $this->fetchTeacherFromUser($user);
    }

}
