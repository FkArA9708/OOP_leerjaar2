<?php
namespace crud_fiets\crudfietsOOP\Tests;

use PHPUnit\Framework\TestCase;
use crud_fiets\crudfietsOOP\Database;

/**
 * @covers \crud_fiets\crudfietsOOP\Database
 */

class DatabaseTest extends TestCase {
    private Database $database;

    protected function setUp(): void {
        $this->database = new Database();
    }

    public function testConstructorCreatesValidInstance(): void {
        $this->assertInstanceOf(Database::class, $this->database);
    }

    public function testGetConnectionReturnsPdoInstance(): void {
        $conn = $this->database->getConnection();
        $this->assertInstanceOf(\PDO::class, $conn);
    }

    public function testDatabaseConnectionIsValid(): void {
        $conn = $this->database->getConnection();
        $this->assertTrue($conn instanceof \PDO);
    }

    public function testDatabasePropertiesAreSet(): void {
        $this->assertEquals('localhost', $this->database->Servername);
        $this->assertEquals('root', $this->database->Username);
        $this->assertEquals('', $this->database->Password);
        $this->assertEquals('fietsenmaker', $this->database->Dbname);
    }

    public function testConnectionCanExecuteQueries(): void {
        $conn = $this->database->getConnection();
        $stmt = $conn->query("SELECT 1 as result");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, $result['result']);
    }
}