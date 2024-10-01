<?php

use PHPUnit\Framework\TestCase;
use App\Models\Player;
use App\Database\Database;

class PlayerTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up the database connection or any required resources
        $this->db = new Database();
        // Create the player model
        $this->player = new Player($this->db);
        
        // Optionally: Seed the database with initial data
        $this->db->execute("INSERT INTO players (name, age, team_id, role, matches_played, runs_scored, wickets_taken) VALUES ('Test Player', 25, 1, 'Batsman', 10, 500, 5);");
    }

    protected function tearDown(): void
    {
        // Clean up the database or resources
        $this->db->execute("DELETE FROM players WHERE name = 'Test Player';");
    }

    public function testGetPlayerById()
    {
        $player = $this->player->getPlayerById(1); // Assuming ID 1 exists
        $this->assertNotNull($player);
        $this->assertEquals('Test Player', $player['name']);
    }

    public function testGetAllPlayers()
    {
        $players = $this->player->getAllPlayers();
        $this->assertIsArray($players);
        $this->assertCount(1, $players);
    }

    public function testCreatePlayer()
    {
        $newPlayerData = [
            'name' => 'New Player',
            'age' => 22,
            'team_id' => 2,
            'role' => 'Bowler',
            'matches_played' => 5,
            'runs_scored' => 200,
            'wickets_taken' => 10
        ];
        $this->player->createPlayer($newPlayerData);
        
        $newPlayer = $this->player->getPlayerById(2); // Assuming this will be the new ID
        $this->assertEquals('New Player', $newPlayer['name']);
    }

    public function testUpdatePlayer()
    {
        $updateData = [
            'name' => 'Updated Player',
            'age' => 26,
            'team_id' => 1,
            'role' => 'All-rounder',
            'matches_played' => 15,
            'runs_scored' => 600,
            'wickets_taken' => 8
        ];
        $this->player->updatePlayer(1, $updateData); // Update player with ID 1
        
        $updatedPlayer = $this->player->getPlayerById(1);
        $this->assertEquals('Updated Player', $updatedPlayer['name']);
        $this->assertEquals(26, $updatedPlayer['age']);
    }

    public function testDeletePlayer()
    {
        $this->player->deletePlayer(1); // Delete player with ID 1
        $deletedPlayer = $this->player->getPlayerById(1);
        $this->assertNull($deletedPlayer);
    }
}
