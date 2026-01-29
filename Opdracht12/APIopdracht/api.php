<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$host = 'localhost';  
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
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}


$method = $_SERVER['REQUEST_METHOD'];
$id = null;
$request_uri = $_SERVER['REQUEST_URI'] ?? '';


if (preg_match('#/(\d+)$#', $request_uri, $matches)) {
    $id = (int)$matches[1];
}

// Validatie voor naam en prijs
function validateProductData($data, $id = null, $pdo) {
    $errors = [];
    //als naam leeg is of meer dan 50 tekens, komt een foutmelding
    if (!isset($data['naam']) || empty(trim($data['naam']))) {
        $errors[] = 'Naam is verplicht';
    } elseif (strlen(trim($data['naam'])) > 50) {
        $errors[] = 'Naam mag maximaal 50 tekens bevatten';
    }
    
    if (!isset($data['prijs']) || !is_numeric($data['prijs'])) {
        $errors[] = 'Prijs is verplicht en moet een getal zijn';
    } elseif ($data['prijs'] < 0) {
        $errors[] = 'Prijs moet 0 of hoger zijn';
    }
    
    // Controle op dubbele naam 
    if (isset($data['naam']) && !empty(trim($data['naam']))) {
        $naam = trim($data['naam']);
        $sql = "SELECT id FROM producten WHERE naam = ?";  
        $params = [$naam];
        
        if ($id) {
            $sql .= " AND id != ?";
            $params[] = $id;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        if ($stmt->fetch()) {
            $errors[] = 'Productnaam moet uniek zijn';
        }
    }
    
    return $errors;
}

// GET METHOD
if ($method === 'GET') {
    try {
        $naam = $_GET['naam'] ?? '';
        
        if ($id) {
            // Specifiek product 
            $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM producten WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            if ($product) { //als een product is, geef http response code 200 terug
                http_response_code(200);
                echo json_encode($product);
            } else { //anders komt een 404 melding waarin een product niet gevonden wordt
                http_response_code(404);
                echo json_encode(['error' => 'Product niet gevonden']);
            }
        } else {
            // Alle producten
            if ($naam) {
                $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM producten WHERE naam LIKE ? ORDER BY id");
                $stmt->execute(["%$naam%"]);
            } else {
                $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM producten ORDER BY id");
                $stmt->execute();
            }
            
            $products = $stmt->fetchAll();
            http_response_code(200);
            echo json_encode($products);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Fout bij ophalen producten: ' . $e->getMessage()]);
    }
    exit();
}

// POST: Nieuw product
if ($method === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Ongeldige JSON data']);
            exit();
        }
        //product data valideren, als er errors bestaan, komt http code 400 terug
        $errors = validateProductData($data, null, $pdo);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => implode(', ', $errors)]);
            exit();
        }
        
        $naam = trim($data['naam']);
        $prijs = (float)$data['prijs'];
        
        //de waardes van producten naam en prijs stoppen in de database
        $stmt = $pdo->prepare("INSERT INTO producten (naam, prijs) VALUES (?, ?)");
        $stmt->execute([$naam, $prijs]);
        
        $newId = $pdo->lastInsertId();
        //product selectie via id
        $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM producten WHERE id = ?");
        $stmt->execute([$newId]);
        $newProduct = $stmt->fetch();
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Product toegevoegd',
            'product' => $newProduct
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Kon product niet toevoegen: ' . $e->getMessage()]);
    }
    exit();
}

// PUT: Update product
if ($method === 'PUT') {
    if (!$id) { //geen id betekent 400 http code
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is vereist voor update']);
        exit();
    }
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($data === null) { 
            http_response_code(400);
            echo json_encode(['error' => 'Ongeldige JSON data']);
            exit();
        }
        
        // Controle of product bestaat 
        $stmt = $pdo->prepare("SELECT id FROM producten WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Product niet gevonden']);
            exit();
        }
        
        $errors = validateProductData($data, $id, $pdo);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => implode(', ', $errors)]);
            exit();
        }
        
        $naam = trim($data['naam']);
        $prijs = (float)$data['prijs'];
        
        //producten bewerken
        $stmt = $pdo->prepare("UPDATE producten SET naam = ?, prijs = ? WHERE id = ?");
        $stmt->execute([$naam, $prijs, $id]);
        
        $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM producten WHERE id = ?");
        $stmt->execute([$id]);
        $updatedProduct = $stmt->fetch();
        
        http_response_code(200);
        echo json_encode([
            'message' => 'Product bijgewerkt',
            'product' => $updatedProduct
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Kon product niet bijwerken: ' . $e->getMessage()]);
    }
    exit();
}

// DELETE: Verwijder product
if ($method === 'DELETE') {
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is vereist voor verwijderen']);
        exit();
    }
    
    try {
        
        $stmt = $pdo->prepare("SELECT id FROM producten WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Product niet gevonden']);
            exit();
        }
        
        
        $stmt = $pdo->prepare("DELETE FROM producten WHERE id = ?");
        $stmt->execute([$id]);
        
        http_response_code(204);
        exit();
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Kon product niet verwijderen: ' . $e->getMessage()]);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Methode niet toegestaan']);
?>