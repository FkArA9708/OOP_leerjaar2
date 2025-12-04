<?php

require_once 'Rekenmachine.php';
require_once 'Rekenmachine2.php';

$calc = new Rekenmachine();

echo "Uitkomst: " . $calc->optellen(3,4);

//var_dump($calc);

$calc = new Rekenmachine2();
$calc->a = 3;
$calc->b = 4;

echo "<br>Uitkomst: " . $calc->optellen();
?>