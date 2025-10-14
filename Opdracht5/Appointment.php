<?php
namespace Hospital;

class Appointment {
    private Patient $patient;
    private Doctor $doctor;
    private array $nurses = [];
    private \DateTime $beginTime;  // Backslash voor globale class
    private \DateTime $endTime;    // Backslash voor globale class
    private static array $allAppointments = [];

    public function __construct(Patient $patient, Doctor $doctor, \DateTime $beginTime, \DateTime $endTime) {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->beginTime = $beginTime;
        $this->endTime = $endTime;
        
        $doctor->addAppointment($this);
        self::$allAppointments[] = $this;
    }

    public static function setAppointment(Patient $patient, Doctor $doctor, array $nurses, \DateTime $beginTime, \DateTime $endTime): Appointment {
        $appointment = new Appointment($patient, $doctor, $beginTime, $endTime);
        
        foreach ($nurses as $nurse) {
            $appointment->addNurse($nurse);
        }
        
        return $appointment;
    }

    public function addNurse(Nurse $nurse): void {
        $this->nurses[] = $nurse;
        $nurse->addAppointment($this);
    }

    public function getDoctor(): Doctor {
        return $this->doctor;
    }

    public function getPatient(): Patient {
        return $this->patient;
    }

    public function getNurses(): array {
        return $this->nurses;
    }

    public function getBeginTime(): string {
        return $this->beginTime->format('Y-m-d H:i:s');
    }

    public function getEndTime(): string {
        return $this->endTime->format('Y-m-d H:i:s');
    }

    public function getTimeDifference(): float {
        $diff = $this->endTime->getTimestamp() - $this->beginTime->getTimestamp();
        return $diff / 3600; // Return hours
    }

    public function getCosts(): float {
        $hours = $this->getTimeDifference();
        $doctorCost = $hours * $this->doctor->getHourlyRate();
        
        $nurseBonus = 0;
        foreach ($this->nurses as $nurse) {
            $nurseBonus += $hours * $nurse->getBonusRate();
        }
        
        return $doctorCost + $nurseBonus;
    }

    public static function getAllAppointments(): array {
        return self::$allAppointments;
    }
}
?>