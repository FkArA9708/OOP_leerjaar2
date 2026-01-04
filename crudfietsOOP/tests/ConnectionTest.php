<?php
// tests/ConnectionTest.php
namespace crud_fiets\crudfietsOOP\tests;

use PHPUnit\Framework\TestCase;
use crud_fiets\crudfietsOOP\Database;

/**
 * @covers \crud_fiets\crudfietsOOP\Database
 */
class ConnectionTest extends TestCase {
    public function testDatabaseConnection() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $this->assertInstanceOf(\PDO::class, $conn);
        
        // Test een simpele query
        $stmt = $conn->query("SELECT COUNT(*) as count FROM fietsen");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertIsNumeric($result['count']);
        echo "\nAantal fietsen in database: " . $result['count'];
    }
}
?>