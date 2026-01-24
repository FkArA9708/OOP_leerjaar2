<?php

// src/classes/Product.php
class Product {
    
    private $id;
    private $naam;
    private $prijs;
    private $created_at;

    
    public function __construct($naam, $prijs, $id = null, $created_at = null) {
        $this->id = $id;
        $this->naam = htmlspecialchars($naam); 
        $this->prijs = (float) $prijs; 
        $this->created_at = $created_at;
    }

    
    public function getId() { return $this->id; }
    public function getNaam() { return $this->naam; }
    public function getPrijs() { return $this->prijs; }
    public function getCreatedAt() { return $this->created_at; }

   
    public function toArray() {
        return [
            'id' => $this->id,
            'naam' => $this->naam,
            'prijs' => $this->prijs,
            'created_at' => $this->created_at
        ];
    }
}
?>