<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Db {
    private $host = 'localhost';
    private $dbname = 'users';  
    private $username = 'root';  
    private $password = '';  
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

$db = new Db();
$pdo = $db->getConnection();

echo "DB connection successful!";