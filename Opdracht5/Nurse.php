<?php

namespace Hospital;
class Nurse extends Staff {
    private float $fixedSalary;
    private float $bonusRate;
    private array $appointments = [];

    public function __construct(string $name, float $fixedSalary, float $bonusRate) {
        parent::__construct($name, "nurse");
        $this->fixedSalary = $fixedSalary;
        $this->bonusRate = $bonusRate;
    }

    public function getRole(): string {
        return "nurse";
    }

    public function calculateSalary(): float {
        $bonus = 0;
        foreach ($this->appointments as $appointment) {
            $bonus += $appointment->getTimeDifference() * $this->bonusRate;
        }
        return $this->fixedSalary + $bonus;
    }

    public function setSalary(float $amount): void {
    $this->salary = $amount;
}

    public function addAppointment(Appointment $appointment): void {
        $this->appointments[] = $appointment;
    }

    public function getBonusRate(): float {
        return $this->bonusRate;
    }
}
?>