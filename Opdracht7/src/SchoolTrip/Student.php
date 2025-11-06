<?php
namespace SchoolTrip;
class Student extends Person {
    private Group $classname;
    private bool $hasPaid = false;
     private static int $studentCount = 0;

    public function __construct(string $name, Group $classname) {
        parent::__construct($name);
        $this->classname = $classname;
        self::$studentCount++; // Teller verhogen
    }

    public static function getStudentCount(): int {
        return self::$studentCount;
    }
    
    public function getRole(): string {
    return "student";
}
   public function setPaid(bool $status): void {
    $this->hasPaid = $status;
}
    public function hasPaid(): bool {
    return $this->hasPaid;
}

public function getClassname(): Group {
        return $this->classname;
    }
}