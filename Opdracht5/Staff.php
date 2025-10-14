<?php

namespace Hospital;
abstract class Staff extends Person {
    protected float $salary;

    public function __construct(string $name, string $role) {
        parent::__construct($name, $role);
    }

    abstract public function setSalary(float $amount): void;

    // Houd calculateSalary ook als abstract
    abstract public function calculateSalary(): float;

    public function getSalary(): float {
        return $this->salary;

    }    
}
?>