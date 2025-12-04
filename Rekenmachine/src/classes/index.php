<?php
//Autoload files using Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';
//require_once 'Rekenmachine.php';
//require_once 'Rekenmachine2.php';

use Rekenmachine\classes\Rekenmachine;
use Rekenmachine\classes\Rekenmachine2;
$calc = new Rekenmachine();

echo "Uitkomst: " . $calc->optellen(3,4);

//var_dump($calc);

$calc = new Rekenmachine2();
$calc->a = 3;
$calc->b = 8;

echo "<br>Uitkomst: " . $calc->optellen();
?>