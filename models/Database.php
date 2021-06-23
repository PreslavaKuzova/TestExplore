<?php
require_once('./models/dto/Question.php');
require_once('./models/dto/User.php');
require_once('./models/dto/Student.php');
require_once('./models/dto/Teacher.php');

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

    function __construct($dbtype = "mysql", $host = "localhost", $username = "root", $pass = "", $dbname = "test_explore")
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
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
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

    function addStudent($email, $firstName, $lastName, $password, $level) : ?Student
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

    function addTeacher($email, $firstName, $lastName, $password, $department) : ?Teacher
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

    function addExam($name, $accessCode, $teacherId, $level)
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO exam(exam_id, name, access_code, date_of_creation, teacher_id, level) 
                        VALUES(:examId, :name, :accessCode, :dateOfCreation, :teacherId, :level)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':examId' => NULL, ':name' => $name, ':accessCode' => $accessCode, ':dateOfCreation' => date('Y-m-d H:i:s'),
                ':teacherId' => $teacherId, ':level' => $level
            ]);

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }
    }

    function addQuestion($questionType, $examId)
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO question(question_id, question_type, exam_id) 
                        VALUES(:questionId, :questionType, :examId)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':questionId' => NULL, ':questionType' => $questionType, ':examId' => $examId
            ]);

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
                $questions[] = new Question($row['question_type'], NULL, NULL);
                //fetching as an associative array
                echo $row['question_id'] . " " . $row['question_type'];
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $questions;
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

    function fetchStudent($email, $password): ?Student
    {
        $user = $this->fetchUser($email, $password);

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

    function fetchTeacher($email, $password): ?Teacher
    {
        $user = $this->fetchUser($email, $password);

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
}
