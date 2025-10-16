<?php
// Functie: programma login OOP 
    // Auteur: Furkan Kara
class Database {
    /**
     * Protected PDO connection property
     * @var PDO
     */
    protected PDO $_conn;

    /**
     * Constructor method to establish database connection
     * @return PDO
     */
    public function __construct() {
        return $this->connectDb();
    }

    /**
     * Establish database connection
     * @return PDO
     */
    protected function connectDb(): PDO {
        try {
            
            $host = 'localhost';
            $dbname = 'login';
            $username = 'root';
            $password = '';
            $charset = 'utf8mb4';

            
            $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

           
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            
            $this->_conn = new PDO($dsn, $username, $password, $options);

            return $this->_conn;

        } catch (PDOException $e) {
            
            error_log('Database Connection Error: ' . $e->getMessage());
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Execute a prepared statement
     * @param string $query SQL query
     * @param array $params Query parameters
     * @return PDOStatement
     */
    public function execute(string $query, array $params = []): PDOStatement {
        try {
            $stmt = $this->_conn->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Query Execution Error: ' . $e->getMessage());
            throw new Exception('Query execution failed: ' . $e->getMessage());
        }
    }

    /**
     * Fetch a single row
     * @param string $query SQL query
     * @param array $params Query parameters
     * @return array|false
     */
    public function fetchSingle(string $query, array $params = []) {
        return $this->execute($query, $params)->fetch();
    }

    /**
     * Fetch all rows
     * @param string $query SQL query
     * @param array $params Query parameters
     * @return array
     */
    public function fetchAll(string $query, array $params = []): array {
        return $this->execute($query, $params)->fetchAll();
    }
}


try {
    
    $db = new Database();

    
    $user = $db->fetchSingle(
        "SELECT * FROM user WHERE gebruiker_id = ?", 
        [1]
    );

    $users = $db->fetchAll(
    "SELECT * FROM user"
    );

} catch (Exception $e) {
   
    echo "Error: " . $e->getMessage();
}
?>
