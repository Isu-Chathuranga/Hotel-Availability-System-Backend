<?php
namespace App\Config;

class Database
{
    private static ?Database $instance = null;
    private ?\PDO $conn = null;
    private string $host = "localhost";
    private string $dbName = "stayvora";
    private string $username = "root";
    private string $password = "";

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        if ($this->conn === null) {
            try {
                $this->conn = new \PDO(
                    "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (\PDOException $e) {
                http_response_code(500);
                echo json_encode(["message" => "Database connection error"]);
                exit;
            }
        }
        return $this->conn;
    }
}
