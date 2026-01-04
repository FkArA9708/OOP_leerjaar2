<?php
namespace crud_fiets\crudfietsOOP\Tests;

use PHPUnit\Framework\TestCase;
use crud_fiets\crudfietsOOP\Fiets;

/**
 * @covers \crud_fiets\crudfietsOOP\Fiets
 */

class FullCrudTest extends TestCase {
    public function testCompleteCrudCycle(): void {
        $fiets = new Fiets();
        
        // CREATE
        $testData = [
            'merk' => 'FullTestMerk_' . uniqid(),
            'type' => 'FullTestType',
            'prijs' => 1500
        ];
        
        $insertResult = $fiets->insertRecord($testData);
        $this->assertTrue($insertResult, "Insert should succeed");
        
        
        $allBikes = $fiets->getData($fiets->table);
        $newBikeId = null;
        
        foreach ($allBikes as $bike) {
            if ($bike['merk'] === $testData['merk']) {
                $newBikeId = $bike['id'];
                break;
            }
        }
        
        $this->assertNotNull($newBikeId, "New bike should be found");
        
        
        $retrievedBike = $fiets->getRecord($newBikeId);
        $this->assertEquals($testData['merk'], $retrievedBike['merk']);
        $this->assertEquals($testData['type'], $retrievedBike['type']);
        $this->assertEquals($testData['prijs'], $retrievedBike['prijs']);
        
        
        $updateData = [
            'id' => $newBikeId,
            'merk' => 'UpdatedFullTestMerk',
            'type' => 'UpdatedFullTestType',
            'prijs' => 2000
        ];
        
        $updateResult = $fiets->updateRecord($updateData);
        $this->assertTrue($updateResult, "Update should succeed");
        
        
        $updatedBike = $fiets->getRecord($newBikeId);
        $this->assertEquals('UpdatedFullTestMerk', $updatedBike['merk']);
        
       
        $deleteResult = $fiets->deleteRecord($newBikeId);
        $this->assertTrue($deleteResult, "Delete should succeed");
        
        
        $deletedBike = $fiets->getRecord($newBikeId);
        $this->assertFalse($deletedBike, "Bike should be deleted");
    }
}