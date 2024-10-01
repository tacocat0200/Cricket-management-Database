<?php
// src/controllers/MatchController.php

namespace Controllers;

use Models\Match;
use Models\Team;
use Helpers\Utils;

class MatchController
{
    protected $matchModel;
    protected $teamModel;

    public function __construct()
    {
        // Initialize the Match and Team models
        $this->matchModel = new Match();
        $this->teamModel = new Team();
    }

    /**
     * List all matches
     */
    public function list()
    {
        // Retrieve all matches from the model
        $matches = $this->matchModel->getAllMatches();

        // Include the view and pass the matches data
        include_once __DIR__ . '/../views/matches/list.php';
    }

    /**
     * Show a single match's details
     * @param int $id - Match ID
     */
    public function view($id)
    {
        // Retrieve match data by ID
        $match = $this->matchModel->getMatchById($id);

        if (!$match) {
            // Handle match not found
            header("HTTP/1.0 404 Not Found");
            echo "Match not found.";
            exit;
        }

        // Include the view and pass the match data
        include_once __DIR__ . '/../views/matches/profile.php';
    }

    /**
     * Show the form to schedule a new match
     */
    public function create()
    {
        // If the form is submitted, handle the POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        // Retrieve teams for selection in the form
        $teams = $this->teamModel->getAllTeams();

        // Include the create match form view
        include_once __DIR__ . '/../views/matches/create.php';
    }

    /**
     * Store a new match in the database
     */
    private function store()
    {
        // Sanitize and validate input data
        $data = [
            'home_team_id' => Utils::sanitize($_POST['home_team_id'] ?? ''),
            'away_team_id' => Utils::sanitize($_POST['away_team_id'] ?? ''),
            'match_date'   => Utils::sanitize($_POST['match_date'] ?? ''),
            'venue'        => Utils::sanitize($_POST['venue'] ?? ''),
            // Add other fields as necessary
        ];

        // Basic validation
        $errors = [];
        if (empty($data['home_team_id'])) {
            $errors[] = "Home team is required.";
        }
        if (empty($data['away_team_id'])) {
            $errors[] = "Away team is required.";
        }
        if ($data['home_team_id'] === $data['away_team_id']) {
            $errors[] = "Home and away teams must be different.";
        }
        if (empty($data['match_date'])) {
            $errors[] = "Match date is required.";
        } elseif (!self::validateDate($data['match_date'])) {
            $errors[] = "Invalid match date format.";
        }
        if (empty($data['venue'])) {
            $errors[] = "Venue is required.";
        }

        if (!empty($errors)) {
            // Retrieve teams for the form
            $teams = $this->teamModel->getAllTeams();

            // Pass errors to the view
            $validationErrors = $errors;
            include_once __DIR__ . '/../views/matches/create.php';
            return;
        }

        // Create the match using the model
        $createSuccess = $this->matchModel->createMatch($data);

        if ($createSuccess) {
            // Redirect to the matches list with a success message
            header("Location: /matches?success=Match scheduled successfully");
            exit;
        } else {
            // Handle creation failure
            $validationErrors = ["Failed to schedule match. Please try again."];
            $teams = $this->teamModel->getAllTeams();
            include_once __DIR__ . '/../views/matches/create.php';
        }
    }

    /**
     * Show the form to edit an existing match
     * @param int $id - Match ID
     */
    public function edit($id)
    {
        // Retrieve match data
        $match = $this->matchModel->getMatchById($id);

        if (!$match) {
            // Handle match not found
            header("HTTP/1.0 404 Not Found");
            echo "Match not found.";
            exit;
        }

        // Retrieve teams for selection in the form
        $teams = $this->teamModel->getAllTeams();

        // If the form is submitted, handle the POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }

        // Include the edit match form view
        include_once __DIR__ . '/../views/matches/edit.php';
    }

    /**
     * Update an existing match in the database
     * @param int $id - Match ID
     */
    private function update($id)
    {
        // Sanitize and validate input data
        $data = [
            'home_team_id' => Utils::sanitize($_POST['home_team_id'] ?? ''),
            'away_team_id' => Utils::sanitize($_POST['away_team_id'] ?? ''),
            'match_date'   => Utils::sanitize($_POST['match_date'] ?? ''),
            'venue'        => Utils::sanitize($_POST['venue'] ?? ''),
            'result'       => Utils::sanitize($_POST['result'] ?? ''),
            // Add other fields as necessary
        ];

        // Basic validation
        $errors = [];
        if (empty($data['home_team_id'])) {
            $errors[] = "Home team is required.";
        }
        if (empty($data['away_team_id'])) {
            $errors[] = "Away team is required.";
        }
        if ($data['home_team_id'] === $data['away_team_id']) {
            $errors[] = "Home and away teams must be different.";
        }
        if (empty($data['match_date'])) {
            $errors[] = "Match date is required.";
        } elseif (!self::validateDate($data['match_date'])) {
            $errors[] = "Invalid match date format.";
        }
        if (empty($data['venue'])) {
            $errors[] = "Venue is required.";
        }

        if (!empty($errors)) {
            // Retrieve teams for the form
            $teams = $this->teamModel->getAllTeams();

            // Pass errors and existing match data to the view
            $validationErrors = $errors;
            $match = $this->matchModel->getMatchById($id);
            include_once __DIR__ . '/../views/matches/edit.php';
            return;
        }

        // Update the match using the model
        $updateSuccess = $this->matchModel->updateMatch($id, $data);

        if ($updateSuccess) {
            // Redirect to the match's profile with a success message
            header("Location: /matches/view/$id?success=Match updated successfully");
            exit;
        } else {
            // Handle update failure
            $validationErrors = ["Failed to update match. Please try again."];
            $teams = $this->teamModel->getAllTeams();
            $match = $this->matchModel->getMatchById($id);
            include_once __DIR__ . '/../views/matches/edit.php';
        }
    }

    /**
     * Delete a match
     * @param int $id - Match ID
     */
    public function delete($id)
    {
        // CSRF protection could be added here

        // Attempt to delete the match using the model
        $deleteSuccess = $this->matchModel->deleteMatch($id);

        if ($deleteSuccess) {
            // Redirect to the matches list with a success message
            header("Location: /matches?success=Match deleted successfully");
            exit;
        } else {
            // Handle deletion failure
            header("Location: /matches?error=Failed to delete match.");
            exit;
        }
    }

    /**
     * Validate date format (YYYY-MM-DD)
     * @param string $date
     * @return bool
     */
    private static function validateDate($date)
    {
        $d = \DateTime::createFromFormat("Y-m-d", $date);
        return $d && $d->format("Y-m-d") === $date;
    }
}
