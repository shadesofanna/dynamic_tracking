<?php
// config/database.php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Initialize dynamic values in the constructor
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'dynamic_pricing_db';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
    }
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION wait_timeout=5, innodb_lock_wait_timeout=5"
                ]
            );
        } catch(PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
        
        return $this->conn;
    }
}
