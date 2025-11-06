<?php
namespace SchoolTrip;

abstract class Person {
    protected string $name;
    
    public function __construct(string $name) { 
    $this->name = $name;
    }
    public function getName(): string { 
    return $this->name;
    }
    abstract public function getRole(): string;
}