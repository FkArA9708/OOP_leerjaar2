<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .welcome { color: green; font-size: 1.2em; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="welcome">
        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    </div>
    
    <h1>Dashboard</h1>
    <p>You have successfully logged in with 2FA.</p>
    
    <a href="logout.php">Logout</a>
</body>
</html>