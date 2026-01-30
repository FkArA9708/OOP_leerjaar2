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
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}


$method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$base_path = '/APIopdracht';
if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}


$id = null;
if (preg_match('#/api\.php/(\d+)$#', $request_uri, $matches)) {
    $id = (int)$matches[1];
} elseif (preg_match('#/(\d+)$#', $request_uri, $matches)) {
    $id = (int)$matches[1];
}

// Function to validate product data
function validateProductData($data, $id = null, $pdo) {
    $errors = [];
    
    // Validate name
    if (!isset($data['naam']) || empty(trim($data['naam']))) { //controle op lege naam en naam meer dan 50 tekens
        $errors[] = 'Naam is verplicht';
    } elseif (strlen(trim($data['naam'])) > 50) {
        $errors[] = 'Naam mag maximaal 50 tekens bevatten';
    }
    
    // prijs validatie
    if (!isset($data['prijs']) || !is_numeric($data['prijs'])) { //bestaat er geen prijs variabel en bevat de prijs geen nummers
        $errors[] = 'Prijs is verplicht en moet een getal zijn';
    } elseif ($data['prijs'] < 0) {
        $errors[] = 'Prijs moet 0 of hoger zijn';
    }
    
    // Dubbele data naam controle
    if (isset($data['naam']) && !empty(trim($data['naam']))) { //als naam niet leeg is en variabel naam bestaat
        $naam = trim($data['naam']);
        $sql = "SELECT id FROM products WHERE LOWER(naam) = LOWER(?)";
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

// GET requests
if ($method === 'GET') {
    try {
        $naam = $_GET['naam'] ?? '';
        
        if ($id) {
            // Haal op een product via id
            $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            if ($product) {
                http_response_code(200);
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product niet gevonden']);
            }
        } else {
            // Alle producten ophalen of zoeken via de naam van de product
            if ($naam) {
                $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM products WHERE naam LIKE ? ORDER BY id");
                $stmt->execute(["%$naam%"]);
            } else {
                $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM products ORDER BY id");
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

// POST requests (voor nieuwe product aanmaken)
if ($method === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'Ongeldige JSON data: ' . json_last_error_msg()]);
            exit();
        }
        
        if (empty($data)) {
            $data = $_POST; 
        }
        
        $errors = validateProductData($data, null, $pdo);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => implode(', ', $errors)]);
            exit();
        }
        
        $naam = trim($data['naam']);
        $prijs = (float)$data['prijs'];
        
        // Insert product
        $stmt = $pdo->prepare("INSERT INTO products (naam, prijs) VALUES (?, ?)");
        $stmt->execute([$naam, $prijs]);
        
        $newId = $pdo->lastInsertId();
        
        // Haal op nieuw gemaakt product
        $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM products WHERE id = ?");
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

// Handle PUT requests (update product)
if ($method === 'PUT') {
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is vereist voor update']);
        exit();
    }
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) { //als er geen data is en een json error bestaat, komt een foutmelding tevoorschijn
            http_response_code(400);
            echo json_encode(['error' => 'Ongeldige JSON data: ' . json_last_error_msg()]);
            exit();
        }
        
        // Controle of een product bestaat
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Product niet gevonden']);
            exit();
        }
        
        $errors = validateProductData($data, $id, $pdo);
        if (!empty($errors)) { //als errors niet leeg zijn, geeft het http code 400
            http_response_code(400);
            echo json_encode(['error' => implode(', ', $errors)]);
            exit();
        }
        
        $naam = trim($data['naam']);
        $prijs = (float)$data['prijs'];
        
        // Update product
        $stmt = $pdo->prepare("UPDATE products SET naam = ?, prijs = ? WHERE id = ?");
        $stmt->execute([$naam, $prijs, $id]);
        
        // Haal updated product
        $stmt = $pdo->prepare("SELECT id, naam, prijs, created_at FROM products WHERE id = ?");
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


if ($method === 'DELETE') {
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is vereist voor verwijderen']);
        exit();
    }
    
    try {
        // Controleer of een product bestaat
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Product niet gevonden']);
            exit();
        }
        
        // Verwijder product
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
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