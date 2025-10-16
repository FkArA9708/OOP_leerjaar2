<?php
// Functie: programma login OOP 
    // Auteur: Furkan Kara
require_once "Database.php";

class User extends Database
{
    public string $username;
    private string $password;
    public string $email;
    public string $role;

    private array $errors;

    public function validateLogin(): array {

    
        $errors = [];

    if (empty($this->username)) {
        array_push($errors, "Username is verplicht");
    } elseif (strlen($this->username) < 3 || strlen($this->username) > 50) {
        array_push($errors, "Username moet tussen 3 en 50 tekens lang zijn");
    }

    if (empty($this->password)) {
        array_push($errors, "Password is verplicht");
    }
    
    return $errors;
    
    
}

function validateRegistration() {
        $errors = [];

        if (empty($this->username)) {
            array_push($errors, "Username is verplicht");
        }

        if (empty($this->password)) {
            array_push($errors, "Password is verplicht");
        }

        if (empty($this->email)) {
            array_push($errors, "Email is verplicht");
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Ongeldig email adres");
        }
        
        return $errors;
    }

public function showUser() {
        echo "<br>Username: $this->username<br>";
        echo "<br>Email: $this->email<br>";
        echo "<br>Voornaam: $this->voornaam<br>";
        echo "<br>Achternaam: $this->achternaam<br>";
    }

public function LoginUser(): bool {
    // Maak database connectie
        $db = new Database();
        $conn = $db->ConnectDb();

        try {
            $stmt = $conn->prepare("SELECT * FROM user WHERE gebruikersnaam = :username");
            $stmt->bindParam(':username', $this->username);
            $stmt->execute();
            
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            
            if (!$user) {
                return false; 
            }
            
            
            if (!password_verify($this->password, $user['wachtwoord'])) {
                return false; 
            }
            
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
           
            session_regenerate_id(true);
            
            
            $_SESSION['gebruiker_id'] = $user['gebruiker_id'];
            $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['voornaam'] = $user['voornaam'];
            $_SESSION['achternaam'] = $user['achternaam'];
            
            return true;
            
        } catch (PDOException $e) {
            
            error_log("Login fout: " . $e->getMessage());
            return false;
        }

        
}
    public function IsLoggedIn(): bool {
             if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['gebruiker_id']) && isset($_SESSION['gebruikersnaam']);
        }

    public function registerUser(): array {
        $errors = [];
        
        if ($this->username != "") {
            // Maak database connectie
            $db = new Database();
            $conn = $db->dbConnect();
            
            // Check of username al bestaat
            $stmt = $conn->prepare("SELECT * FROM user WHERE gebruikersnaam = :username");
            $stmt->bindParam(':username', $this->username);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                array_push($errors, "Username bestaat al.");
            } else {
                // Voeg nieuwe user toe
                $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO user (gebruikersnaam, wachtwoord, email, voornaam, achternaam) VALUES (:gebruikersnaam, :wachtwoord, :email, :voornaam, :achternaam)");
                $stmt->bindParam(':gebruikersnaam', $this->username);
                $stmt->bindParam(':wachtwoord', $hashedPassword);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':voornaam', $this->voornaam);
                $stmt->bindParam(':achternaam', $this->achternaam);
                
                if (!$stmt->execute()) {
                    array_push($errors, "Er ging iets mis bij het registreren.");
                }
            }
        } else {
            array_push($errors, "Username is verplicht.");
        }
        
        return $errors;
    }

    function setPassword($password) {
        $this->password = $password;
    }
    
    function getPassword() {
        return $this->password;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Remove all session variables
        $_SESSION = array();
        
        // Destroy the session
        session_destroy();
        
       
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    public function testConnection() {
        try {
            $db = new Database();
            $conn = $db->ConnectDb();
            $stmt = $conn->prepare("SELECT 1");
            $stmt->execute();
            return "Database verbinding: OK";
        } catch (PDOException $e) {
            return "Database verbinding mislukt: " . $e->getMessage();
        }
    }
}


