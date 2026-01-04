<?php
namespace crud_fiets\crudfietsOOP\Tests;

use PHPUnit\Framework\TestCase;
use crud_fiets\crudfietsOOP\Fiets;

/**
 * @covers \crud_fiets\crudfietsOOP\Fiets
 */

class FietsTest extends TestCase {
    private Fiets $fiets;
    private int $testFietsId = 0;

    protected function setUp(): void {
        $this->fiets = new Fiets();
        
        // Voeg een test fiets toe voor testing
        $testData = [
            'merk' => 'TestMerk_' . uniqid(),
            'type' => 'TestType',
            'prijs' => 999
        ];
        
        $this->fiets->insertRecord($testData);
        
        // Haal de ID op van de net toegevoegde fiets
        $allData = $this->fiets->getData($this->fiets->table);
        foreach ($allData as $fiets) {
            if ($fiets['merk'] === $testData['merk']) {
                $this->testFietsId = (int)$fiets['id'];
                break;
            }
        }
    }

    protected function tearDown(): void {
        // Verwijder test data
        if ($this->testFietsId > 0) {
            $this->fiets->deleteRecord($this->testFietsId);
        }
    }

    public function testConstructorCreatesValidInstance(): void {
        $this->assertInstanceOf(Fiets::class, $this->fiets);
    }

    public function testFietsPropertiesAreInitialized(): void {
        $this->assertEquals('', $this->fiets->Txt);
        $this->assertEquals('fietsen', $this->fiets->table); 
        $this->assertIsArray($this->fiets->values);
        $this->assertIsBool($this->fiets->retVat);
    }

    public function testGetDataReturnsArray(): void {
        $result = $this->fiets->getData($this->fiets->table);
        $this->assertIsArray($result);
        $this->assertGreaterThan(0, count($result));
    }

    public function testGetDataReturnsCorrectStructure(): void {
        $result = $this->fiets->getData($this->fiets->table);
        
        if (count($result) > 0) {
            $firstRow = $result[0];
            $this->assertArrayHasKey('id', $firstRow);
            $this->assertArrayHasKey('merk', $firstRow);
            $this->assertArrayHasKey('type', $firstRow);
            $this->assertArrayHasKey('prijs', $firstRow);
        }
    }

    public function testGetRecordReturnsCorrectData(): void {
        if ($this->testFietsId > 0) {
            $record = $this->fiets->getRecord($this->testFietsId);
            
            $this->assertIsArray($record);
            $this->assertEquals($this->testFietsId, $record['id']);
            $this->assertArrayHasKey('merk', $record);
            $this->assertArrayHasKey('type', $record);
        } else {
            $this->markTestSkipped('Test fiets niet gevonden');
        }
    }

    public function testGetRecordWithInvalidId(): void {
        $record = $this->fiets->getRecord(-1);
        
        $this->assertFalse($record);
    }

    public function testInsertRecordReturnsTrueOnSuccess(): void {
        $testData = [
            'merk' => 'UniekMerk_' . uniqid(),
            'type' => 'TestType',
            'prijs' => 1000
        ];
        
        $result = $this->fiets->insertRecord($testData);
        $this->assertTrue($result);
        
        
        $allData = $this->fiets->getData($this->fiets->table);
        foreach ($allData as $fiets) {
            if ($fiets['merk'] === $testData['merk']) {
                $this->fiets->deleteRecord($fiets['id']);
                break;
            }
        }
    }

    public function testUpdateRecordReturnsTrueOnSuccess(): void {
        if ($this->testFietsId > 0) {
            $updateData = [
                'id' => $this->testFietsId,
                'merk' => 'UpdatedMerk',
                'type' => 'UpdatedType',
                'prijs' => 2000
            ];
            
            $result = $this->fiets->updateRecord($updateData);
            $this->assertTrue($result);
            
            
            $updatedFiets = $this->fiets->getRecord($this->testFietsId);
            $this->assertEquals('UpdatedMerk', $updatedFiets['merk']);
        } else {
            $this->markTestSkipped('Test fiets niet gevonden voor update');
        }
    }

    public function testDeleteRecordReturnsTrueOnSuccess(): void {
        
        $testData = [
            'merk' => 'DeleteTest_' . uniqid(),
            'type' => 'DeleteType',
            'prijs' => 500
        ];
        
        $insertResult = $this->fiets->insertRecord($testData);
        $this->assertTrue($insertResult);
        
        // Haal de ID op
        $allData = $this->fiets->getData($this->fiets->table);
        $deleteId = null;
        
        foreach ($allData as $fiets) {
            if ($fiets['merk'] === $testData['merk']) {
                $deleteId = $fiets['id'];
                break;
            }
        }
        
        if ($deleteId) {
            $result = $this->fiets->deleteRecord($deleteId);
            $this->assertTrue($result);
            
            
            $deletedFiets = $this->fiets->getRecord($deleteId);
            $this->assertFalse($deletedFiets);
        } else {
            $this->markTestSkipped('Test fiets niet gevonden voor delete');
        }
    }

    public function testPrintCrudTableReturnsVoid(): void {
        $result = $this->fiets->getData($this->fiets->table);
        
        
        ob_start();
        $this->fiets->printCrudTable($result);
        $output = ob_get_clean();
        
        $this->assertStringContainsString('<table>', $output);
        $this->assertStringContainsString('</table>', $output);
        $this->assertStringContainsString('<th>', $output);
    }

    public function testCrudMainReturnsVoid(): void {
        ob_start();
        $this->fiets->crudMain();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('<h1>Crud Fietsen</h1>', $output);
        $this->assertStringContainsString('Toevoegen nieuwe fiets', $output);
        $this->assertStringContainsString('<table>', $output);
    }
}