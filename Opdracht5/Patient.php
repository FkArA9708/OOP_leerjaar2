<?php

namespace Hospital;
class Patient extends Person {
    private float $payment;

    public function __construct(string $name, float $payment) {
        parent::__construct($name, "patient");
        $this->payment = $payment;
    }

    public function getRole(): string {
        return "patient";
    }

    public function getPayment(): float {
        return $this->payment;
    }

    public function setPayment(float $payment): void {
        $this->payment = $payment;
    }
}
?>