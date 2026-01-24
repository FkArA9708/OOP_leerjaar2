<?php
// Foutweergave alleen voor ontwikkeling

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'src/classes/ProductApiClient.php';

$apiClient = new ProductApiClient();
$message = '';
$messageType = '';

// FORMULIER VERWERKEN 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    try {
        $naam = trim($_POST['naam']);
        $prijs = trim($_POST['prijs']);
        
        
        if (empty($naam) || empty($prijs) || !is_numeric($prijs)) {
            throw new Exception('Voer een geldige naam en prijs in (prijs moet een getal zijn).');
        }
        
        $apiClient->addProduct($naam, $prijs);
        $message = 'Product succesvol toegevoegd!';
        $messageType = 'success';
        
    } catch (Exception $e) {
        $message = 'Fout: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// ZOEKTERM VERWERKEN
$zoekterm = '';
if (isset($_GET['zoek'])) {
    $zoekterm = htmlspecialchars($_GET['zoek']);
}

// PRODUCTEN OPHALEN VAN API
try {
    $producten = $apiClient->getProducts($zoekterm);
} catch (Exception $e) {
    $message = 'Fout bij ophalen producten: ' . $e->getMessage();
    $messageType = 'error';
    $producten = []; 
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten Beheer - API Client</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 1200px; margin: 40px auto; padding: 20px; background: #f5f7fa; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h1, h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        
        .message { padding: 15px; margin: 20px 0; border-radius: 6px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #34495e; }
        input[type="text"], input[type="number"] { width: 100%; padding: 12px; border: 2px solid #bdc3c7; border-radius: 6px; font-size: 16px; box-sizing: border-box; }
        input:focus { border-color: #3498db; outline: none; }
        
        .btn { background: #3498db; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background 0.3s; }
        .btn:hover { background: #2980b9; }
        .btn-delete { background: #e74c3c; }
        .btn-delete:hover { background: #c0392b; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background-color: #f8f9fa; font-weight: 700; color: #2c3e50; }
        tr:hover { background-color: #f8f9fa; }
        
        .search-box { margin: 25px 0; }
        .flex { display: flex; gap: 15px; align-items: flex-end; }
        .flex-item { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Producten tabel</h1>
        

        <!-- Berichten tonen -->
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- ZOEKFORMULIER -->
        <div class="search-box">
            <h2>Producten Zoeken</h2>
            <form method="GET" action="">
                <div class="flex">
                    <div class="flex-item">
                        <label for="zoek">Zoek op naam:</label>
                        <input type="text" id="zoek" name="zoek" value="<?php echo $zoekterm; ?>" 
                               placeholder="Bijv. Laptop...">
                    </div>
                    <div>
                        <button type="submit" class="btn">Zoeken</button>
                        <?php if ($zoekterm): ?>
                            <a href="?" class="btn" style="background: #95a5a6;">Toon Alles</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- PRODUCTEN TABEL -->
        <h2>Lijst met Producten</h2>
        <?php if (empty($producten)): ?>
            <p>Geen producten gevonden<?php echo $zoekterm ? ' voor "' . htmlspecialchars($zoekterm) . '"' : ''; ?>.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naam</th>
                        <th>Prijs (€)</th>
                        <th>Toegevoegd op</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($producten as $product): ?>
                    <tr>
                        <td>#<?php echo $product->getId(); ?></td>
                        <td><strong><?php echo $product->getNaam(); ?></strong></td>
                        <td>€ <?php echo number_format($product->getPrijs(), 2, ',', '.'); ?></td>
                        <td><?php echo $product->getCreatedAt() ?? 'Onbekend'; ?></td>
                        <td>
                            <!-- Verwijderknop als extra uitbreiding -->
                            <form method="POST" action="" style="display: inline;" 
                                  onsubmit="return confirm('Product #<?php echo $product->getId(); ?> verwijderen?');">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <button type="submit" name="delete_product" class="btn btn-delete">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><em><?php echo count($producten); ?> product(en) gevonden.</em></p>
        <?php endif; ?>

        <!-- TOEVOEGFORMULIER -->
        <h2>Nieuw Product Toevoegen</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="naam">Productnaam:</label>
                <input type="text" id="naam" name="naam" required placeholder="Bijv. Gaming Muis">
            </div>
            <div class="form-group">
                <label for="prijs">Prijs (€):</label>
                <input type="number" id="prijs" name="prijs" step="0.01" min="0" required placeholder="Bijv. 49.99">
            </div>
            <button type="submit" name="add_product" class="btn">Product Toevoegen via API</button>
        </form>

        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
            try {
                if ($apiClient->deleteProduct($_POST['product_id'])) {
                    echo '<div class="message success">Product succesvol verwijderd! Pagina vernieuwen...</div>';
                    echo '<script>setTimeout(() => window.location.reload(), 1500);</script>';
                }
            } catch (Exception $e) {
                echo '<div class="message error">' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
    </div>
</body>
</html>