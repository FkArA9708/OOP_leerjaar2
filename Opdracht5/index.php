<?php
require_once 'person.php';
require_once 'patient.php';
require_once 'staff.php';
require_once 'doctor.php';
require_once 'nurse.php';
require_once 'appointment.php';

// Import de classes uit de Hospital namespace
use Hospital\Patient;
use Hospital\Doctor;
use Hospital\Nurse;
use Hospital\Appointment;

// Create multiple persons for demonstration
$patient1 = new Patient("John Doe", 100.0);
$patient2 = new Patient("Mary Smith", 120.0);
$patient3 = new Patient("Bob Johnson", 90.0);

$doctor1 = new Doctor("Dr. Smith", 50.0);
$doctor2 = new Doctor("Dr. Brown", 60.0);

$nurse1 = new Nurse("Jane Wilson", 2000.0, 0.2);
$nurse2 = new Nurse("Tom Davis", 1800.0, 0.15);
$nurse3 = new Nurse("Lisa Taylor", 2200.0, 0.25);

// Create multiple appointments
$appointment1 = Appointment::setAppointment($patient1, $doctor1, [$nurse1, $nurse2], 
    new DateTime('2024-01-15 10:00:00'), new DateTime('2024-01-15 11:30:00'));

$appointment2 = Appointment::setAppointment($patient2, $doctor2, [$nurse3], 
    new DateTime('2024-01-15 14:00:00'), new DateTime('2024-01-15 15:00:00'));

$appointment3 = Appointment::setAppointment($patient3, $doctor1, [], 
    new DateTime('2024-01-16 09:00:00'), new DateTime('2024-01-16 10:30:00'));

// Get all appointments
$allAppointments = Appointment::getAllAppointments();

// Display as HTML table
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Appointments</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Hospital Appointments</h1>
    
    <table>
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Patient</th>
                <th>Nurses</th>
                <th>Begin Time</th>
                <th>End Time</th>
                <th>Price (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allAppointments as $appointment): ?>
            <tr>
                <td><?php echo htmlspecialchars($appointment->getDoctor()->getName()); ?></td>
                <td><?php echo htmlspecialchars($appointment->getPatient()->getName()); ?></td>
                <td>
                    <?php 
                    $nurseNames = [];
                    foreach ($appointment->getNurses() as $nurse) {
                        $nurseNames[] = $nurse->getName();
                    }
                    echo htmlspecialchars(empty($nurseNames) ? 'None' : implode(', ', $nurseNames));
                    ?>
                </td>
                <td><?php echo htmlspecialchars($appointment->getBeginTime()); ?></td>
                <td><?php echo htmlspecialchars($appointment->getEndTime()); ?></td>
                <td><?php echo number_format($appointment->getCosts(), 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Salary Information</h2>
    <table>
        <thead>
            <tr>
                <th>Staff Member</th>
                <th>Role</th>
                <th>Salary (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $staffMembers = [$doctor1, $doctor2, $nurse1, $nurse2, $nurse3];
            foreach ($staffMembers as $staff): ?>
            <tr>
                <td><?php echo htmlspecialchars($staff->getName()); ?></td>
                <td><?php echo htmlspecialchars($staff->getRole()); ?></td>
                <td><?php echo number_format($staff->calculateSalary(), 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>