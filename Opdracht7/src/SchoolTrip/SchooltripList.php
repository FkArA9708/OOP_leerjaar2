<?php
namespace SchoolTrip;
class SchooltripList {
    private array $students = []; // Student objects
    private ?Teacher $teacher = null;
    
    public function addStudent(Student $student): void { 
        $this->students[] = $student;
    }
    public function setTeacher(Teacher $teacher): void { 
        $this->teacher = $teacher;
    }
    public function getStudents(): array { 
        return $this->students;
    }
    public function getTeacher(): ?Teacher { 
        return $this->teacher;
    }
    public function getPaidCount(): int { 
        $count = 0;
        foreach ($this->students as $student) {
            if ($student->hasPaid()) {
                $count++;
            }
        }
        return $count;
    }
}