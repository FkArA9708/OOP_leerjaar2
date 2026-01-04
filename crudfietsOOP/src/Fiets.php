<?php
namespace crud_fiets\crudfietsOOP;
require_once('Database.php');


class Fiets {
    
    public string $Txt = "";
    private $Result = []; 
    private \PDO $conn;
    private string $sql = "";
    public string $table = "fietsen";
    public array $values = [];
    public bool $retVat = false; 

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->Txt = "";
        $this->Result = [];
        $this->sql = "";
        $this->values = [];
        $this->retVat = false;
    }

   
    
    public function crudMain(): void {
        echo "<h1>Crud Fietsen</h1>
        <nav>
            <a href='insert.php'>Toevoegen nieuwe fiets</a>
        </nav><br>";
        
        $result = $this->getData($this->table);
        $this->printCrudTable($result); 
    }

    public function getData($table): array { 
        $this->sql = "SELECT * FROM $table";
        $query = $this->conn->prepare($this->sql);
        $query->execute();
        $this->Result = $query->fetchAll(); 
        return $this->Result;
    }

    public function getRecord($id): mixed {
        $this->sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $query = $this->conn->prepare($this->sql);
        $query->execute([':id' => $id]);
        return $query->fetch();
    }

    
    public function printCrudTable($result): void {
        $this->printCrudTablePrivate($result); 
    }

    
    private function printCrudTablePrivate($result): void {
        $table = "<table>";
        
        // Print headers
        $headers = array_keys($result[0]);
        $table .= "<tr>";
        foreach($headers as $header) {
            $table .= "<th>" . htmlspecialchars($header) . "</th>";   
        }
        $table .= "<th colspan='2'>Actie</th>";
        $table .= "</tr>";

        // Print rows
        foreach ($result as $row) {
            $table .= "<tr>";
            foreach ($row as $cell) {
                $table .= "<td>" . htmlspecialchars($cell) . "</td>";  
            }
            
            $table .= "<td>
                <form method='post' action='update.php?id=" . $row['id'] . "'>       
                    <button>Wzg</button>	 
                </form></td>";

            $table .= "<td>
                <form method='post' action='delete.php?id=" . $row['id'] . "'>       
                    <button>Verwijder</button>	 
                </form></td>";

            $table .= "</tr>";
        }
        $table .= "</table>";
        
        echo $table;
    }

    public function updateRecord(array $row): bool {
        $this->sql = "UPDATE {$this->table} 
                SET merk = :merk, type = :type, prijs = :prijs 
                WHERE id = :id";
        
        $this->values = [
            ':merk' => $row['merk'],
            ':type' => $row['type'],
            ':prijs' => $row['prijs'],
            ':id' => $row['id']
        ];

        $stmt = $this->conn->prepare($this->sql);
        $stmt->execute($this->values);
        
        $this->retVat = ($stmt->rowCount() == 1);
        return $this->retVat;
    }

    public function insertRecord($post): bool {
        $this->sql = "INSERT INTO {$this->table} (merk, type, prijs)
                VALUES (:merk, :type, :prijs)";
        
        $this->values = [
            ':merk' => $post['merk'],
            ':type' => $post['type'],
            ':prijs' => $post['prijs']
        ];

        try {
            $stmt = $this->conn->prepare($this->sql);
            $stmt->execute($this->values);
            $this->retVat = ($stmt->rowCount() == 1);
            return $this->retVat;
        } catch (\PDOException $e) {
            $this->retVat = false;
            return $this->retVat;
        }
    }

    public function deleteRecord($id): bool {
        $this->sql = "DELETE FROM {$this->table} WHERE id = :id";
        
        $this->values = [':id' => $id];
        
        $stmt = $this->conn->prepare($this->sql);
        $stmt->execute($this->values);
        
        $this->retVat = ($stmt->rowCount() == 1);
        return $this->retVat;
    }
}
?>