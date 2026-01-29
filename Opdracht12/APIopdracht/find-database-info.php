<?php
echo "<h1>Database Information Test</h1><pre>";


$test_combinations = [
    ['host' => 'localhost', 'dbname' => 'st1738846988'],
    ['host' => 'localhost', 'dbname' => 'st1738846988_producten'],
    ['host' => 'mysql.splsites.nl', 'dbname' => 'st1738846988'],
    ['host' => 'mysql.splsites.nl', 'dbname' => 'st1738846988_producten'],
];

$username = 'st1738846988';
$password = 'FFQJ1aBV7B8oasj';

foreach ($test_combinations as $test) {
    echo "\n=== Testing: host={$test['host']}, dbname={$test['dbname']} ===\n";
    
    try {
        $pdo = new PDO(
            "mysql:host={$test['host']};dbname={$test['dbname']};charset=utf8mb4",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo "✓ Connection successful!\n";
        
        // Test welke tabellen er zijn
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Tables in database:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
        
        // controleer producten table
        if (in_array('producten', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM producten");
            $result = $stmt->fetch();
            echo "✓ Table 'producten' exists with {$result['count']} records\n";
        }
        
    } catch (PDOException $e) {
        echo "✗ Connection failed: " . $e->getMessage() . "\n";
    }
}

// Test zonder database naam 
echo "\n=== Testing available databases on localhost ===\n";
try {
    $pdo = new PDO(
        "mysql:host=localhost;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Available databases:\n";
    foreach ($databases as $db) {
        echo "- $db\n";
        
        // Als database naam begint met je gebruikersnaam, test dan tabellen
        if (strpos($db, 'st1738846988') === 0) {
            $pdo2 = new PDO(
                "mysql:host=localhost;dbname=$db;charset=utf8mb4",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT]
            );
            
            $stmt2 = $pdo2->query("SHOW TABLES");
            if ($stmt2) {
                $tables = $stmt2->fetchAll(PDO::FETCH_COLUMN);
                echo "  Tables in $db:\n";
                foreach ($tables as $table) {
                    echo "  - $table\n";
                }
            }
        }
    }
    
} catch (PDOException $e) {
    echo "✗ Failed to list databases: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>