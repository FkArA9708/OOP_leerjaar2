<?php
    // Functie: programma login OOP 
    // Auteur: Studentnaam

    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    
    include 'classes/User.php';

    //Main
    $piet = new User();
    $piet->username = "Piet";

    $piet->showUser();

    $jan = new User();
    $jan->username = "Jan";
    $jan->showUser();

?>