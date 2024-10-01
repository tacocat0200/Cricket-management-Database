<?php
// src/models/Player.php

namespace Models;

use PDO;
use PDOException;

class Player
{
    protected $db;

    /**
     * Constructor
     *
     * Initializes the database connection.
     */
    public function __construct()
    {
        try {
            // Replace the placeholders with your actual database credentials
            $host = 'localhost';
            $dbname = 'yourdatabase';
            $username = 'yourusername';
            $password = 'yourpassword';

            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            // Set PDO error mode to exception for better error handling
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection errors (you might want to log this in a real application)
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Retrieve all players with team names.
     *
     * @return array An array of all players.
     */
    public function getAllPlayers()
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                p.position,
                p.team_id,
                t.name AS team_name,
                p.photo_path,
                p.statistics
            FROM 
                players p
            LEFT JOIN 
                teams t ON p.team_id = t.id
            ORDER BY 
                p.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single player by their ID.
     *
     * @param int $id The ID of the player.
     * @return array|false The player data or false if not found.
     */
    public function getPlayerById($id)
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                p.position,
                p.team_id,
                t.name AS team_name,
                p.photo_path,
                p.statistics
            FROM 
                players p
            LEFT JOIN 
                teams t ON p.team_id = t.id
            WHERE 
                p.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new player.
     *
     * @param array $data The player data.
     * @return bool True on success, false on failure.
     */
    public function createPlayer($data)
    {
        $sql = "
            INSERT INTO players (name, position, team_id, photo_path, statistics)
            VALUES (:name, :position, :team_id, :photo_path, :statistics)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'        => $data['name'],
            'position'    => $data['position'],
            'team_id'     => $data['team_id'],
            'photo_path'  => $data['photo_path'] ?? null,
            'statistics'  => json_encode($data['statistics'] ?? []), // Assuming statistics are stored as JSON
        ]);
    }

    /**
     * Update an existing player.
     *
     * @param int   $id   The ID of the player to update.
     * @param array $data The updated player data.
     * @return bool True on success, false on failure.
     */
    public function updatePlayer($id, $data)
    {
        $sql = "
            UPDATE players 
            SET 
                name = :name, 
                position = :position, 
                team_id = :team_id, 
                photo_path = :photo_path, 
                statistics = :statistics 
            WHERE 
                id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'        => $data['name'],
            'position'    => $data['position'],
            'team_id'     => $data['team_id'],
            'photo_path'  => $data['photo_path'] ?? $this->getPlayerById($id)['photo_path'],
            'statistics'  => json_encode($data['statistics'] ?? $this->getPlayerById($id)['statistics']),
            'id'          => $id,
        ]);
    }

    /**
     * Delete a player by their ID.
     *
     * @param int $id The ID of the player to delete.
     * @return bool True on success, false on failure.
     */
    public function deletePlayer($id)
    {
        $sql = "DELETE FROM players WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    /**
     * Retrieve players by team ID.
     *
     * @param int $teamId The ID of the team.
     * @return array An array of players in the specified team.
     */
    public function getPlayersByTeamId($teamId)
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                p.position,
                p.team_id,
                t.name AS team_name,
                p.photo_path,
                p.statistics
            FROM 
                players p
            LEFT JOIN 
                teams t ON p.team_id = t.id
            WHERE 
                p.team_id = :team_id
            ORDER BY 
                p.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['team_id' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search players based on criteria.
     *
     * @param array $criteria The search criteria (e.g., name, position, team_id).
     * @return array An array of players matching the criteria.
     */
    public function searchPlayers($criteria)
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                p.position,
                p.team_id,
                t.name AS team_name,
                p.photo_path,
                p.statistics
            FROM 
                players p
            LEFT JOIN 
                teams t ON p.team_id = t.id
            WHERE 
                1=1
        ";

        $params = [];

        if (!empty($criteria['name'])) {
            $sql .= " AND p.name LIKE :name";
            $params['name'] = '%' . $criteria['name'] . '%';
        }

        if (!empty($criteria['position'])) {
            $sql .= " AND p.position = :position";
            $params['position'] = $criteria['position'];
        }

        if (!empty($criteria['team_id'])) {
            $sql .= " AND p.team_id = :team_id";
            $params['team_id'] = $criteria['team_id'];
        }

        $sql .= " ORDER BY p.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update player statistics.
     *
     * @param int   $id         The ID of the player.
     * @param array $statistics The statistics to update.
     * @return bool True on success, false on failure.
     */
    public function updatePlayerStatistics($id, $statistics)
    {
        $currentData = $this->getPlayerById($id);
        if (!$currentData) {
            return false;
        }

        $existingStats = json_decode($currentData['statistics'], true) ?? [];
        $updatedStats = array_merge($existingStats, $statistics);

        $sql = "
            UPDATE players 
            SET 
                statistics = :statistics 
            WHERE 
                id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'statistics' => json_encode($updatedStats),
            'id'         => $id,
        ]);
    }
}
