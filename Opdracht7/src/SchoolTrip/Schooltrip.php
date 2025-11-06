<?php
namespace SchoolTrip;
class Schooltrip {
    private string $name;
    private float $price;
    private array $lists = []; // SchooltripList objects
    
    public function __construct(string $name, float $price) {
        $this->name = $name;
        $this->price = $price;
     }

     public function getName(): string {
        return $this->name;
    }

    public function addList(SchooltripList $list): void { 
        $this->lists[] = $list;
    }

    public function getSchooltripLists(): array {
    return $this->lists;
}

    public function addStudent(Student $student, Teacher $teacher): void {
        foreach ($this->lists as $list) {
            if ($list->getTeacher() === $teacher) {
                $list->addStudent($student);
                return;
            }
        }
     }
    public function getTotalRevenue(): float { 
        $total = 0.0;
        foreach ($this->lists as $list) {
            foreach ($list->getStudents() as $student) {
                if ($student->hasPaid()) {
                    $total += $this->price;
                }
            }
        }
        return $total;
    }

    // Andere bereken-methodes hier
    public function getRevenueByGroup(Group $group): float { 
        $total = 0.0;
        foreach ($this->lists as $list) {
            foreach ($list->getStudents() as $student) {
                if ($student->hasPaid() && in_array($student, $group->getStudents(), true)) {
                    $total += $this->price;
                }
            }
        }
        return $total;
    }

    public function getParticipationPercentage(Group $group): float { 
        $totalStudents = count($group->getStudents());
        if ($totalStudents === 0) {
            return 0.0;
        }
        $paidStudents = 0;
        foreach ($this->lists as $list) {
            foreach ($list->getStudents() as $student) {
                if ($student->hasPaid() && in_array($student, $group->getStudents(), true)) {
                    $paidStudents++;
                }
            }
        }
        return ($paidStudents / $totalStudents) * 100;
    }
}

