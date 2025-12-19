<?php
session_start();

require_once 'GoogleAuthenticator.php';
use PHPGangsta\GoogleAuthenticator;

// Check if user came from login
if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if (isset($_POST['verify'])) {
    $userCode = $_POST['code'];
    $secret = $_SESSION['temp_2fa_secret'];
    
    $ga = new GoogleAuthenticator();
    
    if ($ga->verifyCode($secret, $userCode)) {
        // 2FA successful, log in user
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $_SESSION['temp_user_id'];
        $_SESSION['username'] = $_SESSION['temp_username'];
        
        // Clean up temp session
        unset($_SESSION['temp_user_id']);
        unset($_SESSION['temp_username']);
        unset($_SESSION['temp_2fa_secret']);
        
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid 2FA code!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 5px; margin: 5px 0; }
        input[type="submit"] { padding: 10px 20px; background: #17a2b8; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #138496; }
        .error { color: red; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>2FA Verification</h1>
    
    <?php if ($message): ?>
        <div class="error"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <p>Please enter the 2FA code from your Google Authenticator app:</p>
    
    <form method="post" action="verify2fa.php">
        <label for="code">2FA Code:</label>
        <input type="text" id="code" name="code" required maxlength="6" pattern="[0-9]{6}"><br><br>
        
        <input type="submit" name="verify" value="Verify">
    </form>
</body>
</html>