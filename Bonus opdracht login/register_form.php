<?php
    // Functie: programma login OOP 
    // Auteur: Furkan Kara
    
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require_once('classes/User.php');

    $user = new User();
    $errors = [];

    
    if(isset($_POST['register-btn'])){
        
        
        $user->username = $_POST['username'];
        $user->setPassword($_POST['password']);
        $user->email = $_POST['email'];
        $user->voornaam = $_POST['voornaam'];
        $user->achternaam = $_POST['achternaam'];

        
        $errors = $user->validateRegistration();

        
        if(count($errors) == 0){
            
            $registerErrors = $user->registerUser();
            $errors = array_merge($errors, $registerErrors);
        }
        
        if(count($errors) > 0){
            echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
            echo "<strong>Fouten gevonden:</strong><br>";
            foreach ($errors as $error) {
                echo "- " . htmlspecialchars($error) . "<br>";
            }
            echo "</div>";
        } else {
            echo "<div style='color: green;'>Gebruiker succesvol geregistreerd! U kunt nu inloggen.</div>";
            
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login_form.php';
                }, 2000);
            </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registratie</title>
</head>
<body>
    
    <h3>PHP - PDO Login and Registration</h3>
    <hr/>

    <form action="" method="POST">    
        <h4>Register here...</h4>
        <hr>
        
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />
        </div>
        <br>
        <div>
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <br>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
        </div>
        <br>
        <div>
            <label>Voornaam</label>
            <input type="text" name="voornaam" value="<?php echo isset($_POST['voornaam']) ? htmlspecialchars($_POST['voornaam']) : ''; ?>" required />
        </div>
        <br>
        <div>
            <label>Achternaam</label>
            <input type="text" name="achternaam" value="<?php echo isset($_POST['achternaam']) ? htmlspecialchars($_POST['achternaam']) : ''; ?>" required />
        </div>
        <br />
        <div>
            <button type="submit" name="register-btn">Register</button>
        </div>
        <br>
        <a href="index.php">Home</a>
    </form>

</body>
</html>