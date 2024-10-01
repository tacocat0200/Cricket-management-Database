<?php
// src/controllers/PlayerController.php

namespace Controllers;

use Models\Player;
use Helpers\Utils;

class PlayerController
{
    protected $playerModel;

    public function __construct()
    {
        // Initialize the Player model
        $this->playerModel = new Player();
    }

    /**
     * List all players
     */
    public function list()
    {
        // Retrieve all players from the model
        $players = $this->playerModel->getAllPlayers();

        // Include the view and pass the players data
        include_once __DIR__ . '/../views/players/list.php';
    }

    /**
     * Show a single player's profile
     * @param int $id - Player ID
     */
    public function view($id)
    {
        // Retrieve player data by ID
        $player = $this->playerModel->getPlayerById($id);

        if (!$player) {
            // Handle player not found
            header("HTTP/1.0 404 Not Found");
            echo "Player not found.";
            exit;
        }

        // Include the view and pass the player data
        include_once __DIR__ . '/../views/players/profile.php';
    }

    /**
     * Show the form to create a new player
     */
    public function create()
    {
        // If the form is submitted, handle the POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        // Include the create player form view
        include_once __DIR__ . '/../views/players/create.php';
    }

    /**
     * Store a new player in the database
     */
    private function store()
    {
        // Sanitize and validate input data
        $data = [
            'name' => Utils::sanitize($_POST['name'] ?? ''),
            'position' => Utils::sanitize($_POST['position'] ?? ''),
            'team_id' => Utils::sanitize($_POST['team_id'] ?? ''),
            'photo' => $_FILES['photo'] ?? null,
            // Add other fields as necessary
        ];

        // Basic validation
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Name is required.";
        }
        if (empty($data['position'])) {
            $errors[] = "Position is required.";
        }
        if (empty($data['team_id'])) {
            $errors[] = "Team is required.";
        }

        // Handle file upload if a photo is provided
        if ($data['photo'] && $data['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Utils::uploadFile($data['photo'], 'player_photos');
            if ($uploadResult['success']) {
                $data['photo_path'] = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            // Pass errors to the view
            $validationErrors = $errors;
            include_once __DIR__ . '/../views/players/create.php';
            return;
        }

        // Create the player using the model
        $createSuccess = $this->playerModel->createPlayer($data);

        if ($createSuccess) {
            // Redirect to the players list with a success message
            header("Location: /players?success=Player created successfully");
            exit;
        } else {
            // Handle creation failure
            $validationErrors = ["Failed to create player. Please try again."];
            include_once __DIR__ . '/../views/players/create.php';
        }
    }

    /**
     * Show the form to edit an existing player
     * @param int $id - Player ID
     */
    public function edit($id)
    {
        // Retrieve player data
        $player = $this->playerModel->getPlayerById($id);

        if (!$player) {
            // Handle player not found
            header("HTTP/1.0 404 Not Found");
            echo "Player not found.";
            exit;
        }

        // If the form is submitted, handle the POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }

        // Include the edit player form view
        include_once __DIR__ . '/../views/players/edit.php';
    }

    /**
     * Update an existing player in the database
     * @param int $id - Player ID
     */
    private function update($id)
    {
        // Sanitize and validate input data
        $data = [
            'name' => Utils::sanitize($_POST['name'] ?? ''),
            'position' => Utils::sanitize($_POST['position'] ?? ''),
            'team_id' => Utils::sanitize($_POST['team_id'] ?? ''),
            'photo' => $_FILES['photo'] ?? null,
            // Add other fields as necessary
        ];

        // Basic validation
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Name is required.";
        }
        if (empty($data['position'])) {
            $errors[] = "Position is required.";
        }
        if (empty($data['team_id'])) {
            $errors[] = "Team is required.";
        }

        // Handle file upload if a new photo is provided
        if ($data['photo'] && $data['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Utils::uploadFile($data['photo'], 'player_photos');
            if ($uploadResult['success']) {
                $data['photo_path'] = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            // Pass errors and existing player data to the view
            $validationErrors = $errors;
            $player = $this->playerModel->getPlayerById($id);
            include_once __DIR__ . '/../views/players/edit.php';
            return;
        }

        // Update the player using the model
        $updateSuccess = $this->playerModel->updatePlayer($id, $data);

        if ($updateSuccess) {
            // Redirect to the player's profile with a success message
            header("Location: /players/view/$id?success=Player updated successfully");
            exit;
        } else {
            // Handle update failure
            $validationErrors = ["Failed to update player. Please try again."];
            $player = $this->playerModel->getPlayerById($id);
            include_once __DIR__ . '/../views/players/edit.php';
        }
    }

    /**
     * Delete a player
     * @param int $id - Player ID
     */
    public function delete($id)
    {
        // CSRF protection could be added here

        // Attempt to delete the player using the model
        $deleteSuccess = $this->playerModel->deletePlayer($id);

        if ($deleteSuccess) {
            // Redirect to the players list with a success message
            header("Location: /players?success=Player deleted successfully");
            exit;
        } else {
            // Handle deletion failure
            header("Location: /players?error=Failed to delete player.");
            exit;
        }
    }
}
