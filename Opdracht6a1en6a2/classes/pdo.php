<?php
class Database {
    private string $host = "localhost";
    private string $dbname = "login";
    private string $username = "root";  
    private string $password = "";      
    private ?PDO $connection = null;

    public function dbConnect(): PDO {
        if ($this->connection !== null) {
            return $this->connection;
        }

        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            // Zet error mode op exceptions
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $this->connection;
            
        } catch (PDOException $e) {
            throw new PDOException("Database connectie mislukt: " . $e->getMessage());
        }
    }
}