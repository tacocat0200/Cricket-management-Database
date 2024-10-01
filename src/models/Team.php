<?php
// src/models/Team.php

namespace Models;

use PDO;
use PDOException;

class Team
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
     * Retrieve all teams with player counts.
     *
     * @return array An array of all teams.
     */
    public function getAllTeams()
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.coach,
                t.ranking,
                t.logo_path,
                COUNT(p.id) AS player_count
            FROM 
                teams t
            LEFT JOIN 
                players p ON t.id = p.team_id
            WHERE
                t.deleted_at IS NULL
            GROUP BY 
                t.id, t.name, t.coach, t.ranking, t.logo_path
            ORDER BY 
                t.ranking ASC, t.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single team by its ID.
     *
     * @param int $id The ID of the team.
     * @return array|false The team data or false if not found.
     */
    public function getTeamById($id)
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.coach,
                t.ranking,
                t.logo_path,
                t.created_at,
                t.updated_at
            FROM 
                teams t
            WHERE 
                t.id = :id AND t.deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new team.
     *
     * @param array $data The team data.
     * @return bool True on success, false on failure.
     */
    public function createTeam($data)
    {
        $sql = "
            INSERT INTO teams (name, coach, ranking, logo_path)
            VALUES (:name, :coach, :ranking, :logo_path)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'       => $data['name'],
            'coach'      => $data['coach'],
            'ranking'    => $data['ranking'],
            'logo_path'  => $data['logo_path'] ?? null,
        ]);
    }

    /**
     * Update an existing team.
     *
     * @param int   $id   The ID of the team to update.
     * @param array $data The updated team data.
     * @return bool True on success, false on failure.
     */
    public function updateTeam($id, $data)
    {
        $sql = "
            UPDATE teams 
            SET 
                name = :name, 
                coach = :coach, 
                ranking = :ranking, 
                logo_path = :logo_path 
            WHERE 
                id = :id AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'       => $data['name'],
            'coach'      => $data['coach'],
            'ranking'    => $data['ranking'],
            'logo_path'  => $data['logo_path'] ?? $this->getTeamById($id)['logo_path'],
            'id'         => $id,
        ]);
    }

    /**
     * Delete a team by its ID (Soft Delete).
     *
     * @param int $id The ID of the team to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteTeam($id)
    {
        $sql = "UPDATE teams SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    /**
     * Retrieve teams by ranking range.
     *
     * @param int $minRanking The minimum ranking.
     * @param int $maxRanking The maximum ranking.
     * @return array An array of teams within the specified ranking range.
     */
    public function getTeamsByRankingRange($minRanking, $maxRanking)
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.coach,
                t.ranking,
                t.logo_path,
                COUNT(p.id) AS player_count
            FROM 
                teams t
            LEFT JOIN 
                players p ON t.id = p.team_id
            WHERE
                t.ranking BETWEEN :min_ranking AND :max_ranking
                AND t.deleted_at IS NULL
            GROUP BY 
                t.id, t.name, t.coach, t.ranking, t.logo_path
            ORDER BY 
                t.ranking ASC, t.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'min_ranking' => $minRanking,
            'max_ranking' => $maxRanking,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search teams based on criteria.
     *
     * @param array $criteria The search criteria (e.g., name, coach).
     * @return array An array of teams matching the criteria.
     */
    public function searchTeams($criteria)
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.coach,
                t.ranking,
                t.logo_path,
                COUNT(p.id) AS player_count
            FROM 
                teams t
            LEFT JOIN 
                players p ON t.id = p.team_id
            WHERE 
                t.deleted_at IS NULL
        ";

        $params = [];

        if (!empty($criteria['name'])) {
            $sql .= " AND t.name LIKE :name";
            $params['name'] = '%' . $criteria['name'] . '%';
        }

        if (!empty($criteria['coach'])) {
            $sql .= " AND t.coach LIKE :coach";
            $params['coach'] = '%' . $criteria['coach'] . '%';
        }

        $sql .= " GROUP BY t.id, t.name, t.coach, t.ranking, t.logo_path";

        // Additional sorting or ordering can be added based on criteria

        $sql .= " ORDER BY t.ranking ASC, t.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve teams along with their players.
     *
     * @return array An array of teams with their respective players.
     */
    public function getTeamsWithPlayers()
    {
        $sql = "
            SELECT 
                t.id AS team_id,
                t.name AS team_name,
                t.coach,
                t.ranking,
                t.logo_path,
                p.id AS player_id,
                p.name AS player_name,
                p.position,
                p.photo_path,
                p.statistics
            FROM 
                teams t
            LEFT JOIN 
                players p ON t.id = p.team_id
            WHERE 
                t.deleted_at IS NULL
            ORDER BY 
                t.ranking ASC, t.name ASC, p.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $teams = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $teamId = $row['team_id'];
            if (!isset($teams[$teamId])) {
                $teams[$teamId] = [
                    'id'         => $row['team_id'],
                    'name'       => $row['team_name'],
                    'coach'      => $row['coach'],
                    'ranking'    => $row['ranking'],
                    'logo_path'  => $row['logo_path'],
                    'players'    => [],
                ];
            }

            if ($row['player_id']) {
                $teams[$teamId]['players'][] = [
                    'id'          => $row['player_id'],
                    'name'        => $row['player_name'],
                    'position'    => $row['position'],
                    'photo_path'  => $row['photo_path'],
                    'statistics'  => json_decode($row['statistics'], true) ?? [],
                ];
            }
        }

        return array_values($teams);
    }
}
