<?php


namespace Eejay\Data;

use PDO;

class Database
{

    /**
     * Database configurations.
     */
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = '';
    const DATABASE_PASS = '';
    const DATABASE_NAME = '';

    /**
     * @var PDO
     */
    private $database;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        try {
            $pdo = new PDO('mysql:host=' . self::DATABASE_HOST . ';dbname=' . self::DATABASE_NAME . ';charset=utf8', self::DATABASE_USER, self::DATABASE_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->database = $pdo;
            return $this->database;
        } catch (PDOException $ex) {
            die('failed to connect to the database');
        }
    }

    /**
     * Get taught question - answer set.
     *
     * @param $question
     * @return mixed
     */
    public function getAnswer($question)
    {
        $pdo = $this->database;
        $stmt = $pdo->prepare('SELECT answer FROM question_bank WHERE question = ? ORDER BY answer DESC');
        $stmt->bindValue(1, $question);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['answer'];
    }

    /**
     * Teach SimpSimp with question - answer set.
     *
     * @param $question
     * @param $answer
     * @param $ipAddress
     * @return int
     */
    public function insertSet($question, $answer): int
    {
        if (empty($question) || empty($answer))
            return 0;

        $pdo = $this->database;
        $stmt = $pdo->prepare('INSERT INTO question_bank (question, answer) VALUES (?, ?)');
        $stmt->bindValue(1, $question);
        $stmt->bindValue(2, $answer);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * Store user question - answer set.
     *
     * @param $ipAddress
     * @param $set
     * @return int
     */
    public function store($ipAddress, $set)
    {
        $pdo = $this->database;
        $stmt = $pdo->prepare('INSERT INTO user_set (ip_address, data_set) VALUES (?, ?)');
        $stmt->bindValue(1, $ipAddress);
        $stmt->bindValue(2, $set);
        $stmt->execute();

        return $stmt->rowCount();
    }

}