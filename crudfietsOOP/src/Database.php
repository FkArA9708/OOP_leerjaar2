<?php
namespace crud_fiets\crudfietsOOP;



class Database {
    public string $Servername;
    public string $Username;
    public string $Password;
    public string $Dbname;
    public \PDO $conn;

    public function __construct() {
        $this->Servername = 'localhost';
        $this->Username = 'root';
        $this->Password = '';
        $this->Dbname = 'fietsenmaker';
        $this->connect();
    }



    private function connect(): void {
        try {
            $this->conn = new \PDO(
                "mysql:host={$this->Servername};dbname={$this->Dbname}",
                $this->Username,
                $this->Password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );
        } catch(\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): \PDO {
        return $this->conn;
    }
}
?>