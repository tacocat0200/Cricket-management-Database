<?php
// src/controllers/TeamController.php

namespace Controllers;

use Models\Team;
use Models\Player;
use Helpers\Utils;

class TeamController
{
    protected $teamModel;
    protected $playerModel;

    public function __construct()
    {
        // Initialize the Team and Player models
        $this->teamModel = new Team();
        $this->playerModel = new Player();
    }

    /**
     * List all teams
     */
    public function list()
    {
        // Retrieve all teams from the model
        $teams = $this->teamModel->getAllTeams();

        // Include the view and pass the teams data
        include_once __DIR__ . '/../views/teams/list.php';
    }

    /**
     * Show a single team's profile, including its roster
     * @param int $id - Team ID
     */
    public function view($id)
    {
        // Retrieve team data by ID
        $team = $this->teamModel->getTeamById($id);

        if (!$team) {
            // Handle team not found
            header("HTTP/1.0 404 Not Found");
            echo "Team not found.";
            exit;
        }

        // Retrieve players in the team
        $players = $this->playerModel->getPlayersByTeamId($id);

        // Include the view and pass the team and players data
        include_once __DIR__ . '/../views/teams/profile.php';
    }

    /**
     * Show the form to create a new team
     */
    public function create()
    {
        // If the form is submitted, handle the POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        // Include the create team form view
        include_once __DIR__ . '/../views/teams/create.php';
    }

    /**
     * Store a new team in the database
     */
    private function store()
    {
        // Sanitize and validate input data
        $data = [
            'name' => Utils::sanitize($_POST['name'] ?? ''),
            'coach' => Utils::sanitize($_POST['coach'] ?? ''),
            'ranking' => Utils::sanitize($_POST['ranking'] ?? ''),
            'logo' => $_FILES['logo'] ?? null,
            // Add other fields as necessary
        ];

        // Basic validation
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Team name is required.";
        }
        if (empty($data['coach'])) {
            $errors[] = "Coach name is required.";
        }
        if (!is_numeric($data['ranking']) || $data['ranking'] < 1) {
            $errors[] = "Ranking must be a positive number.";
        }

        // Handle file upload if a logo is provided
        if ($data['logo'] && $data['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Utils::uploadFile($data['logo'], 'team_logos');
            if ($uploadResult['success']) {
                $data['logo_path'] = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            // Pass errors to the view
            $validationErrors = $errors;
            include_once __DIR__ . '/../views/teams/create.php';
            return;
        }

        // Create the team using the model
        $createSuccess = $this->teamModel->createTeam($data);

        if ($createSuccess) {
            // Redirect to the teams list with a success message
            header("Location: /teams?success=Team created successfully");
            exit;
        } else {
            // Handle creation failure
            $validationErrors = ["Failed to create team. Please try again."];
            include_once __DIR__ . '/../views/teams/create.php';
        }
    }

    /**
     * Show the form to edit an existing team
     * @param int $id - Team ID
     */
    public function edit($id)
    {
        // Retrieve team data
        $team = $this->teamModel->getTeamById($id);

        if (!$team) {
            // Handle team not found
            header("HTTP/1.0 404 Not Found");
            echo "Team not found.";
            exit;
        }

        // If the form is submitted, handle the POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }

        // Include the edit team form view
        include_once __DIR__ . '/../views/teams/edit.php';
    }

    /**
     * Update an existing team in the database
     * @param int $id - Team ID
     */
    private function update($id)
    {
        // Sanitize and validate input data
        $data = [
            'name' => Utils::sanitize($_POST['name'] ?? ''),
            'coach' => Utils::sanitize($_POST['coach'] ?? ''),
            'ranking' => Utils::sanitize($_POST['ranking'] ?? ''),
            'logo' => $_FILES['logo'] ?? null,
            // Add other fields as necessary
        ];

        // Basic validation
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Team name is required.";
        }
        if (empty($data['coach'])) {
            $errors[] = "Coach name is required.";
        }
        if (!is_numeric($data['ranking']) || $data['ranking'] < 1) {
            $errors[] = "Ranking must be a positive number.";
        }

        // Handle file upload if a new logo is provided
        if ($data['logo'] && $data['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Utils::uploadFile($data['logo'], 'team_logos');
            if ($uploadResult['success']) {
                $data['logo_path'] = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            // Pass errors and existing team data to the view
            $validationErrors = $errors;
            $team = $this->teamModel->getTeamById($id);
            include_once __DIR__ . '/../views/teams/edit.php';
            return;
        }

        // Update the team using the model
        $updateSuccess = $this->teamModel->updateTeam($id, $data);

        if ($updateSuccess) {
            // Redirect to the team's profile with a success message
            header("Location: /teams/view/$id?success=Team updated successfully");
            exit;
        } else {
            // Handle update failure
            $validationErrors = ["Failed to update team. Please try again."];
            $team = $this->teamModel->getTeamById($id);
            include_once __DIR__ . '/../views/teams/edit.php';
        }
    }

    /**
     * Delete a team
     * @param int $id - Team ID
     */
    public function delete($id)
    {
        // CSRF protection could be added here

        // Attempt to delete the team using the model
        $deleteSuccess = $this->teamModel->deleteTeam($id);

        if ($deleteSuccess) {
            // Redirect to the teams list with a success message
            header("Location: /teams?success=Team deleted successfully");
            exit;
        } else {
            // Handle deletion failure
            header("Location: /teams?error=Failed to delete team.");
            exit;
        }
    }
}
