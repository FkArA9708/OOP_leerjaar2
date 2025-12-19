<?php
session_start();

$db_host = "localhost";
$db_user = "root";  // ← andere naam dan $username
$db_pass = "";      // ← andere naam dan $password
$db_name = "authenticator";

// Include GoogleAuthenticator
require_once 'GoogleAuthenticator.php';

use PHPGangsta\GoogleAuthenticator;

// Initialize variables
$qrCodeUrl = null;
$secret = null;
$message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $form_username = $_POST['username'];  // duidelijke naam
        $form_password = $_POST['password'];
        
        // Hash the password
        $password_hash = password_hash($form_password, PASSWORD_DEFAULT);
        
        try {
            // Create database connection
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create Google Authenticator instance
            $ga = new GoogleAuthenticator();
            
            // Generate secret
            $secret = $ga->createSecret();
            
            // Insert user into database
            $sql = "INSERT INTO users (username, password, 2fa_secret) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$form_username, $password_hash, $secret]);
            
            // Generate QR code URL
            $qrCodeUrl = $ga->getQRCodeGoogleUrl('TCRHELDEN', $secret);
            
            $message = "Registration successful!";
            
        } catch(PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registreren met 2FA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="password"] { padding: 5px; margin: 5px 0; }
        input[type="submit"] { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #0056b3; }
        .message { color: green; margin: 10px 0; }
        .error { color: red; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Register</h1>
    
    <?php if ($message): ?>
        <div class="<?php echo (strpos($message, 'Error') !== false) ? 'error' : 'message'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="registreren.php">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Registreer">
    </form>
    
    <?php if ($qrCodeUrl): ?>
        <h3>Registratie succesvol! Scan deze QR code met Google Authenticator:</h3>
        <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code"><br>
        <p>Sla de geheime sleutel op: <?php echo $secret; ?></p>
    <?php endif; ?>
    
    <a href="login.php">Login</a>
</body>
</html>