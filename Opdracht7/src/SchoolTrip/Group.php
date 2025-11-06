<?php
namespace SchoolTrip;
class Group {
    private string $name;
    
    private array $students = [];

    public function __construct(string $name) {
        $this->name = $name;
     }
    public function getName(): string { 
        return $this->name;
    }

    public function addStudent(Student $student): void { 
        $this->students[] = $student;
}    public function getStudents(): array {
        return $this->students;
    }
}

