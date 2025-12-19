<?php
session_start();

// Database configuration
$servername = "localhost";
$dbusername = "root"; // Adjust as needed
$dbpassword = ""; // Adjust as needed
$dbname = "authenticator";

$message = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT id, password, 2fa_secret FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Check if user exists
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Check if 2FA is required
                if (!empty($user['2fa_secret'])) {
                    // Store user info in session for 2FA verification
                    $_SESSION['temp_user_id'] = $user['id'];
                    $_SESSION['temp_username'] = $username;
                    $_SESSION['temp_2fa_secret'] = $user['2fa_secret'];
                    
                    // Redirect to 2FA verification page
                    header("Location: verify2fa.php");
                    exit();
                } else {
                    // No 2FA, log in directly
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    
                    // Redirect to the user's dashboard
                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "User not found!";
        }
        
    } catch(PDOException $e) {
        $message = "Connection failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="password"] { padding: 5px; margin: 5px 0; }
        input[type="submit"] { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #218838; }
        .error { color: red; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Login</h1>
    
    <?php if ($message): ?>
        <div class="error"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input id="username" name="username" required type="text" /><br><br>
        
        <label for="password">Password:</label>
        <input id="password" name="password" required type="password" /><br><br>
        
        <input name="login" type="submit" value="Login" />
    </form>
    
    <p>Don't have an account? <a href="registreren.php">Register here</a></p>
</body>
</html>