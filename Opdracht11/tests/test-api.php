<?php
// test-api.php
require_once 'src/classes/ProductApiClient.php';
$client = new ProductApiClient();
try {
    $products = $client->getProducts();
    echo "API werkt! " . count($products) . " producten gevonden.";
} catch (Exception $e) {
    echo "Fout: " . $e->getMessage();
}
?>