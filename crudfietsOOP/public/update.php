<?php
require_once '../src/Fiets.php';

require_once __DIR__ . '/../vendor/autoload.php';



use crud_fiets\crudfietsOOP\Fiets;

$fiets = new Fiets();

if(isset($_POST['btn_wzg'])) {
    if($fiets->updateRecord($_POST)) {
        echo "<script>alert('Fiets is gewijzigd')</script>";
        echo "<script>location.replace('index.php');</script>";
    } else {
        echo '<script>alert("Fiets is NIET gewijzigd")</script>';
    }
}

if(isset($_GET['id'])) {
    $row = $fiets->getRecord($_GET['id']);
} else {
    echo "Geen id opgegeven<br>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Wijzig Fiets</title>
</head>
<body>
    <h2>Wijzig Fiets</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        
        <label for="merk">Merk:</label>
        <input type="text" id="merk" name="merk" required value="<?php echo $row['merk']; ?>"><br><br>
        
        <label for="type">Type:</label>
        <input type="text" id="type" name="type" required value="<?php echo $row['type']; ?>"><br><br>
        
        <label for="prijs">Prijs:</label>
        <input type="number" id="prijs" name="prijs" required value="<?php echo $row['prijs']; ?>"><br><br>
        
        <button type="submit" name="btn_wzg">Wijzig</button>
    </form>
    <br><br>
    <a href='index.php'>Home</a>
</body>
</html>