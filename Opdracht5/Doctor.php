<?php

namespace Hospital;
class Doctor extends Staff {
    private float $hourlyRate;
    private array $appointments = [];

    public function __construct(string $name, float $hourlyRate) {
        parent::__construct($name, "doctor");
        $this->hourlyRate = $hourlyRate;
    }

    public function getRole(): string {
        return "doctor";
    }

    public function calculateSalary(): float {
        $totalHours = 0;
        foreach ($this->appointments as $appointment) {
            $totalHours += $appointment->getTimeDifference();
        }
        return $totalHours * $this->hourlyRate;
    }

    public function getHourlyRate(): float {
        return $this->hourlyRate;
    }

    public function setSalary(float $amount): void {
    $this->salary = $amount;
}

    public function addAppointment(Appointment $appointment): void {
        $this->appointments[] = $appointment;
    }
}
?>