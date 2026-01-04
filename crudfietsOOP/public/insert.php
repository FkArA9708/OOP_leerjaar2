<?php
require_once __DIR__ . '/../vendor/autoload.php';

use crud_fiets\crudfietsOOP\Fiets;

if(isset($_POST['btn_ins'])) {
    $fiets = new Fiets();
    
    if($fiets->insertRecord($_POST)) {
        echo "<script>alert('Fiets is toegevoegd')</script>";
        echo "<script>location.replace('index.php');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Fiets</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Insert Fiets</h1>
    <form method="post">
        <label for="merk">Merk:</label>
        <input type="text" id="merk" name="merk" required><br><br>
        
        <label for="type">Type:</label>
        <input type="text" id="type" name="type" required><br><br>
        
        <label for="prijs">Prijs:</label>
        <input type="number" id="prijs" name="prijs" required><br><br>
        
        <button type="submit" name="btn_ins">Insert</button>
    </form>
    
    <br><br>
    <a href='index.php'>Home</a>
</body>
</html>