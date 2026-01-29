<?php

echo "<h1>Testing SPL Database Connection</h1><pre>";

$host = 'mysql.splsites.nl';
$dbname = 'st1738846988';
$username = 'st1738846988';
$password = 'FFQJ1aBV7B8oasj'; 

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "Database connectie voltooid\n\n";
    
    // controleren of een tabel bestaat in database
    $stmt = $pdo->query("SHOW TABLES LIKE 'producten'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "Table 'producten' bestaan\n\n";
        
        
        $stmt = $pdo->query("DESCRIBE producten");
        $columns = $stmt->fetchAll();
        
        echo "Tabel structuur:\n";
        foreach ($columns as $col) { //alles vanuit de tabel weergeven
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
        echo "\n";
        
        
        $stmt = $pdo->query("SELECT * FROM producten LIMIT 5");
        $products = $stmt->fetchAll();
        
        echo "Sample data:\n";
        print_r($products);
        
    } else {
        echo "Tabel 'producten' bestaat niet!\n";
        echo "Tabel wordt gemaakt...\n";
        
        $sql = "CREATE TABLE IF NOT EXISTS producten (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            naam VARCHAR(50) NOT NULL UNIQUE,
            prijs DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "Tabel 'producten' gemaakt\n";
    }
    
} catch (PDOException $e) {
    echo "Connectie fout: " . $e->getMessage() . "\n";
    echo "Bekijk accountgegevens vanuit SPL in email.\n";
}

echo "</pre>";
?>