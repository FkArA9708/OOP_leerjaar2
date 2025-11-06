<?php
spl_autoload_register(function ($class) {
    require_once 'src/' . str_replace('\\', '/', $class) . '.php';
});

use SchoolTrip\Group;
use SchoolTrip\Teacher;
use SchoolTrip\Student;
use SchoolTrip\SchooltripList;
use SchoolTrip\Schooltrip;

// [Je bestaande PHP code voor het aanmaken van objecten blijft hetzelfde]
// Maak groepen aan
$classA = new Group("Sod2a");
$classB = new Group("Sod2b");

// Maak leraren aan
$teacher1 = new Teacher("Rob Wigmans");
$teacher2 = new Teacher("Barry van Helden");
$teacher3 = new Teacher("Floris van Drimmelen");
$teacher4 = new Teacher("Carolien Frankhuijzen");
$teacher5 = new Teacher("Jan van der Brugge");
// Maak studenten aan en wijs ze toe aan groepen
$student1 = new Student("Furkan Kara", $classB);
$student2 = new Student("Dainius", $classB);
$student3 = new Student("Rojvan", $classB);
$student4 = new Student("Jan", $classB);
$student5 = new Student("Vitaliy", $classB);
$student6 = new Student("Ramon", $classB);
$student7 = new Student("Igor", $classB);
$student8 = new Student("Amani", $classB);
$student9 = new Student("Furkan Kondu", $classB);
$student10 = new Student("Berke", $classB);
$student11 = new Student("Danny", $classB);
$student12 = new Student("Harun", $classB);
$student13 = new Student("Jeremiah", $classB);
$student14 = new Student("Pascal", $classB);
$student15 = new Student("Abou", $classA);
$student16 = new Student("Adam", $classA);
$student17 = new Student("Ali Akbas", $classA);
$student18 = new Student("Bernardo", $classA);
$student19 = new Student("Elwin", $classA);
$student20 = new Student("Erkin", $classA);
$student21 = new Student("Jayden", $classA);
$student22 = new Student("Mahir", $classA);
$classA->addStudent($student15);
$classA->addStudent($student16);
$classA->addStudent($student17);
$classA->addStudent($student18);
$classA->addStudent($student19);
$classA->addStudent($student20);
$classA->addStudent($student21);
$classA->addStudent($student22);
$classB->addStudent($student1);
$classB->addStudent($student2);
$classB->addStudent($student3);
$classB->addStudent($student4);
$classB->addStudent($student5);
$classB->addStudent($student6);
$classB->addStudent($student7);
$classB->addStudent($student8);
$classB->addStudent($student9);
$classB->addStudent($student10);
$classB->addStudent($student11);
$classB->addStudent($student12);
$classB->addStudent($student13);
$classB->addStudent($student14);
// Maak schoolreis aan
$schooltrip = new Schooltrip("Efteling", 20.0);
// Maak lijsten aan voor elke leraar

// Maak eerst SchooltripList objecten aan
$list1 = new SchooltripList();
$list2 = new SchooltripList();
$list3 = new SchooltripList();
$list4 = new SchooltripList();
$list5 = new SchooltripList();
$list1->setTeacher($teacher1);
$list2->setTeacher($teacher2);
$list3->setTeacher($teacher3);
$list4->setTeacher($teacher4);
$list5->setTeacher($teacher5);
$schooltrip->addList($list1);
$schooltrip->addList($list2);
$schooltrip->addList($list3);
$schooltrip->addList($list4);
$schooltrip->addList($list5);
// Alle 22 studenten toevoegen aan de juiste lijsten via de schoolreis
$schooltrip->addStudent($student1, $teacher1);
$schooltrip->addStudent($student2, $teacher1); 
$schooltrip->addStudent($student3, $teacher2);
$schooltrip->addStudent($student4, $teacher2);
$schooltrip->addStudent($student5, $teacher3);
$schooltrip->addStudent($student6, $teacher3);
$schooltrip->addStudent($student7, $teacher4);
$schooltrip->addStudent($student8, $teacher4);
$schooltrip->addStudent($student9, $teacher5);
$schooltrip->addStudent($student10, $teacher5);
$schooltrip->addStudent($student11, $teacher1);
$schooltrip->addStudent($student12, $teacher1);
$schooltrip->addStudent($student13, $teacher2);
$schooltrip->addStudent($student14, $teacher2);
$schooltrip->addStudent($student15, $teacher3);
$schooltrip->addStudent($student16, $teacher3);
$schooltrip->addStudent($student17, $teacher4);
$schooltrip->addStudent($student18, $teacher4);
$schooltrip->addStudent($student19, $teacher5);
$schooltrip->addStudent($student20, $teacher5);
$schooltrip->addStudent($student21, $teacher1);
$schooltrip->addStudent($student22, $teacher1);
// Markeer sommige studenten als betaald
$student1->setPaid(true);
$student3->setPaid(true);
$student5->setPaid(true);
$student7->setPaid(true);
$student9->setPaid(true);
$student11->setPaid(true);
$student13->setPaid(true);
$student15->setPaid(true);
$student17->setPaid(true);
$student19->setPaid(true);
$student21->setPaid(true);
$totalRevenue = $schooltrip->getTotalRevenue();
$revenueGroupA = $schooltrip->getRevenueByGroup($classA);
$revenueGroupB = $schooltrip->getRevenueByGroup($classB);
$participationA = $schooltrip->getParticipationPercentage($classA);
$participationB = $schooltrip->getParticipationPercentage($classB);

// Start HTML output
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schooluitje Overzicht</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .teacher-row {
            background-color: #e8f5e8 !important;
            font-weight: bold;
        }
        .paid-yes {
            color: green;
            font-weight: bold;
        }
        .paid-no {
            color: red;
        }
        .summary {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Schooluitje Overzicht - <?php echo $schooltrip->getName(); ?></h1>
        
        <h2>Deelnemerslijst</h2>
        <table>
            <thead>
                <tr>
                    <th>Docent</th>
                    <th>Student</th>
                    <th>Klas</th>
                    <th>Betaald</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lists = $schooltrip->getSchooltripLists();
                foreach ($lists as $list) {
                    $teacher = $list->getTeacher();
                    $students = $list->getStudents();
                    
                    $firstStudent = true;
                    foreach ($students as $student) {
                        $paidStatus = $student->hasPaid() ? 'Ja' : 'Nee';
                        $paidClass = $student->hasPaid() ? 'paid-yes' : 'paid-no';
                        $className = $student->getClassname()->getName();
                        
                        echo "<tr>";
                        if ($firstStudent && $teacher) {
                            echo "<td class='teacher-row'>" . htmlspecialchars($teacher->getName()) . "</td>";
                            $firstStudent = false;
                        } else {
                            echo "<td></td>";
                        }
                        echo "<td>" . htmlspecialchars($student->getName()) . "</td>";
                        echo "<td>" . htmlspecialchars($className) . "</td>";
                        echo "<td class='$paidClass'>" . htmlspecialchars($paidStatus) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="summary">
    <h2>Financiële Samenvatting</h2>
    <p><strong>Totaal opgehaald:</strong> €<?php echo $totalRevenue; ?></p>
    <p><strong><?php echo $classA->getName(); ?>:</strong> €<?php echo $revenueGroupA; ?> (<?php echo $participationA; ?>% deelname)</p>
    <p><strong><?php echo $classB->getName(); ?>:</strong> €<?php echo $revenueGroupB; ?> (<?php echo $participationB; ?>% deelname)</p>
    <!-- Static property display -->
    <p><strong>Totaal aantal studenten:</strong> <?php echo Student::getStudentCount(); ?></p>
</div>
</body>
</html>