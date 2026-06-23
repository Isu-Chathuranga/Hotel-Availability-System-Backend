<?php
// config/database.php
// Database connection settings. Update these to match your MySQL setup.

class Database
{
    private $host = "localhost";
    private $db_name = "auth_app";
    private $username = "root";
    private $password = ""; // set your MySQL password here
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Database connection error: " . $exception->getMessage()
            ]);
            exit();
        }

        return $this->conn;
    }
}
