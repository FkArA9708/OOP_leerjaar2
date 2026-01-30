<?php
// Test the API endpoints
echo "<h1>API Test</h1>";

// Test GET all products
echo "<h2>GET all products:</h2>";
$ch = curl_init('http://localhost/APIopdracht/api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Test POST a product
echo "<h2>POST new product:</h2>";
$data = ['naam' => 'Test Product', 'prijs' => 19.99]; 
$ch = curl_init('http://localhost/APIopdracht/api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl instellingen
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// id parsen van json naar php
$result = json_decode($response, true); 
$id = $result['product']['id'] ?? null;

if ($id) {
    // Test GET specifieke product
    echo "<h2>GET product $id:</h2>";
    $ch = curl_init("http://localhost/APIopdracht/api.php/$id"); //id achter url voor specifiek product
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    // Test PUT (update) product
    echo "<h2>PUT update product $id:</h2>";
    $data = ['naam' => 'Updated Product', 'prijs' => 29.99];
    $ch = curl_init("http://localhost/APIopdracht/api.php/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    // Test DELETE product
    echo "<h2>DELETE product $id:</h2>";
    $ch = curl_init("http://localhost/APIopdracht/api.php/$id"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    $response = curl_exec($ch);
    echo "HTTP Code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
}
?>