<?php
echo "<h1>Test localhost database connectie</h1><pre>";


$host = 'localhost';
$dbname = 'producten';
$username = 'root';
$password = '';

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
    
    echo "Database connectie gelukt!\n\n";
    
   
    $stmt = $pdo->query("SHOW TABLES LIKE 'products'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "Tabel 'products' bestaat!\n\n";
        
        
        $stmt = $pdo->query("DESCRIBE products");
        $columns = $stmt->fetchAll();
        
        echo "Tabel structuur:\n";
        foreach ($columns as $col) {
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
        echo "\n";
        
       
        $stmt = $pdo->query("SELECT * FROM products LIMIT 5");
        $products = $stmt->fetchAll();
        
        echo "Sample data:\n";
        print_r($products);
        
    } else {
        echo "Tabel 'products' bestaat niet!\n";
        echo "Tabel aanmaken...\n";
        //maakt een producten tabel als er eentje niet bestaat
        $sql = "CREATE TABLE IF NOT EXISTS products ( 
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            naam VARCHAR(50) NOT NULL UNIQUE,
            prijs DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "Table 'products' gemaakt!\n";
        
        
        $testData = [
            ['Laptop', 899.99],
            ['Muis', 29.99],
            ['Toetsenbord', 49.99]
        ];
        
        foreach ($testData as $data) {
            $stmt = $pdo->prepare("INSERT INTO products (naam, prijs) VALUES (?, ?)");
            $stmt->execute($data);
        }
        
        echo "Test data inserted!\n";
    }
    
} catch (PDOException $e) {
    echo "Connectie gefaald: " . $e->getMessage() . "\n";
    echo "Zorg ervoor dat:\n";
    echo "1. MySQL werkt (XAMPP)\n";
    echo "2. Database 'producten' bestaat\n";
    echo "3. Username: root, Password: (leeg)\n";
}

echo "</pre>";
?>