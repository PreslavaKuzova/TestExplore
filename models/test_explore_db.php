<?php
require_once('./dto/question.php');

class Database
{
    const UNVALID_ID = -1;

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
        $insertedId = self::UNVALID_ID;
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

    function addStudent($email, $firstName, $lastName, $password, $level)
    {
        $insertedId = $this->addUser($email, $firstName, $lastName, $password);
        if ($insertedId != self::UNVALID_ID) {
            try {
                $this->connection->beginTransaction();

                $sql = "INSERT INTO student(student_id, user_id, level) VALUES(:studentId, :userId, :level)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([':studentId' => NULL, ':userId' => $insertedId, ':level' => $level]);

                $this->connection->commit();
            } catch (PDOException $e) {
                $this->connection->rollBack();
                echo $e->getMessage();
            }
        }
    }

    function addTeacher($email, $firstName, $lastName, $password, $department)
    {
        $insertedId = $this->addUser($email, $firstName, $lastName, $password);
        if ($insertedId != self::UNVALID_ID) {
            try {
                $this->connection->beginTransaction();

                $sql = "INSERT INTO teacher(teacher_id, user_id, department) VALUES(:teacherId, :userId, :department)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([':teacherId' => NULL, ':userId' => $insertedId, ':department' => $department]);

                $this->connection->commit();
            } catch (PDOException $e) {
                $this->connection->rollBack();
                echo $e->getMessage();
            }
        }
    }

    function addExam($accessCode, $teacherId, $level)
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO exam(exam_id, access_code, date_of_creation, teacher_id, level) 
                        VALUES(:examId, :accessCode, :dateOfCreation, :teacherId, :level)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':examId' => NULL, ':accessCode' => $accessCode, ':dateOfCreation' => date('Y-m-d H:i:s'),
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

    function fetchAllExamQuestions($examId)
    {
        $questions = array();

        try {
            $sql = "SELECT * FROM question WHERE exam_id=:examId";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":examId", $examId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $questions[] = new Question($row['question_type'], NULL, NULL);
                //fetching like associative array
                echo $row['question_id'] . " " . $row['question_type'];
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }

        return $questions;
    }
}
