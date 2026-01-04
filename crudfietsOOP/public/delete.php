<?php
require_once '../src/Fiets.php';

require_once __DIR__ . '/../vendor/autoload.php';


use crud_fiets\crudfietsOOP\Fiets;

if(isset($_GET['id'])) {
    $fiets = new Fiets();
    
    if($fiets->deleteRecord($_GET['id'])) {
        echo '<script>alert("Fietscode: ' . $_GET['id'] . ' is verwijderd")</script>';
        echo "<script>location.replace('index.php');</script>";
    } else {
        echo '<script>alert("Fiets is NIET verwijderd")</script>';
    }
}
?>