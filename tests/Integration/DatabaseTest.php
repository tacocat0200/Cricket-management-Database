<?php

use PHPUnit\Framework\TestCase;
use App\Database\Database;

class DatabaseTest extends TestCase
{
    protected $db;

    protected function setUp(): void
    {
        // Create a new instance of the Database class before each test
        $this->db = new Database();
        
        // Optionally, create a test table
        $this->db->execute("CREATE TABLE IF NOT EXISTS test_players (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            age INT NOT NULL
        );");
    }

    protected function tearDown(): void
    {
        // Drop the test table after each test
        $this->db->execute("DROP TABLE IF EXISTS test_players;");
    }

    public function testDatabaseConnection()
    {
        // Check if the database connection is established
        $this->assertNotNull($this->db->getConnection());
    }

    public function testInsertData()
    {
        // Insert a test record into the test_players table
        $this->db->execute("INSERT INTO test_players (name, age) VALUES ('John Doe', 30);");
        
        // Verify the data was inserted correctly
        $result = $this->db->query("SELECT * FROM test_players WHERE name = 'John Doe';");
        $this->assertCount(1, $result);
        $this->assertEquals('John Doe', $result[0]['name']);
        $this->assertEquals(30, $result[0]['age']);
    }

    public function testUpdateData()
    {
        // Insert a test record
        $this->db->execute("INSERT INTO test_players (name, age) VALUES ('Jane Doe', 28);");
        
        // Update the record
        $this->db->execute("UPDATE test_players SET age = 29 WHERE name = 'Jane Doe';");
        
        // Verify the data was updated correctly
        $result = $this->db->query("SELECT * FROM test_players WHERE name = 'Jane Doe';");
        $this->assertEquals(29, $result[0]['age']);
    }

    public function testDeleteData()
    {
        // Insert a test record
        $this->db->execute("INSERT INTO test_players (name, age) VALUES ('Test Player', 25);");
        
        // Delete the record
        $this->db->execute("DELETE FROM test_players WHERE name = 'Test Player';");
        
        // Verify the record was deleted
        $result = $this->db->query("SELECT * FROM test_players WHERE name = 'Test Player';");
        $this->assertCount(0, $result);
    }
}
