<?php
namespace SchoolTrip;
class Teacher extends Person {
    private float $salary;

     public function __construct(string $name, float $salary = 0) {
        parent::__construct($name); // Parent constructor aanroepen
        $this->salary = $salary;
    }
    
    public function getRole(): string { 
        return "teacher";
    }

    public function getSalary(): float {
        return $this->salary;
    }
    // Teacher-specifieke methodes hier
}