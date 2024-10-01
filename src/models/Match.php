<?php
// src/models/Match.php

namespace Models;

use PDO;
use PDOException;

class Match
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
     * Retrieve all matches with team names.
     *
     * @return array An array of all matches.
     */
    public function getAllMatches()
    {
        $sql = "
            SELECT 
                m.id,
                m.home_team_id,
                ht.name AS home_team,
                m.away_team_id,
                at.name AS away_team,
                m.match_date,
                m.venue,
                m.result
            FROM 
                matches m
            JOIN 
                teams ht ON m.home_team_id = ht.id
            JOIN 
                teams at ON m.away_team_id = at.id
            ORDER BY 
                m.match_date DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single match by its ID.
     *
     * @param int $id The ID of the match.
     * @return array|false The match data or false if not found.
     */
    public function getMatchById($id)
    {
        $sql = "
            SELECT 
                m.id,
                m.home_team_id,
                ht.name AS home_team,
                m.away_team_id,
                at.name AS away_team,
                m.match_date,
                m.venue,
                m.result
            FROM 
                matches m
            JOIN 
                teams ht ON m.home_team_id = ht.id
            JOIN 
                teams at ON m.away_team_id = at.id
            WHERE 
                m.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new match.
     *
     * @param array $data The match data.
     * @return bool True on success, false on failure.
     */
    public function createMatch($data)
    {
        $sql = "
            INSERT INTO matches (home_team_id, away_team_id, match_date, venue, result)
            VALUES (:home_team_id, :away_team_id, :match_date, :venue, :result)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'match_date'   => $data['match_date'],
            'venue'        => $data['venue'],
            'result'       => $data['result'] ?? null, // Assuming result is optional at creation
        ]);
    }

    /**
     * Update an existing match.
     *
     * @param int   $id   The ID of the match to update.
     * @param array $data The updated match data.
     * @return bool True on success, false on failure.
     */
    public function updateMatch($id, $data)
    {
        $sql = "
            UPDATE matches 
            SET 
                home_team_id = :home_team_id, 
                away_team_id = :away_team_id, 
                match_date = :match_date, 
                venue = :venue, 
                result = :result 
            WHERE 
                id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'match_date'   => $data['match_date'],
            'venue'        => $data['venue'],
            'result'       => $data['result'] ?? null, // Assuming result can be updated or set
            'id'           => $id,
        ]);
    }

    /**
     * Delete a match by its ID.
     *
     * @param int $id The ID of the match to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteMatch($id)
    {
        $sql = "DELETE FROM matches WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    /**
     * Retrieve matches by team ID.
     *
     * @param int $teamId The ID of the team.
     * @return array An array of matches involving the specified team.
     */
    public function getMatchesByTeamId($teamId)
    {
        $sql = "
            SELECT 
                m.id,
                m.home_team_id,
                ht.name AS home_team,
                m.away_team_id,
                at.name AS away_team,
                m.match_date,
                m.venue,
                m.result
            FROM 
                matches m
            JOIN 
                teams ht ON m.home_team_id = ht.id
            JOIN 
                teams at ON m.away_team_id = at.id
            WHERE 
                m.home_team_id = :team_id OR m.away_team_id = :team_id
            ORDER BY 
                m.match_date DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['team_id' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve upcoming matches.
     *
     * @return array An array of upcoming matches.
     */
    public function getUpcomingMatches()
    {
        $sql = "
            SELECT 
                m.id,
                m.home_team_id,
                ht.name AS home_team,
                m.away_team_id,
                at.name AS away_team,
                m.match_date,
                m.venue
            FROM 
                matches m
            JOIN 
                teams ht ON m.home_team_id = ht.id
            JOIN 
                teams at ON m.away_team_id = at.id
            WHERE 
                m.match_date >= CURDATE()
            ORDER BY 
                m.match_date ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve past matches.
     *
     * @return array An array of past matches.
     */
    public function getPastMatches()
    {
        $sql = "
            SELECT 
                m.id,
                m.home_team_id,
                ht.name AS home_team,
                m.away_team_id,
                at.name AS away_team,
                m.match_date,
                m.venue,
                m.result
            FROM 
                matches m
            JOIN 
                teams ht ON m.home_team_id = ht.id
            JOIN 
                teams at ON m.away_team_id = at.id
            WHERE 
                m.match_date < CURDATE()
            ORDER BY 
                m.match_date DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
