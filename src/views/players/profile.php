<?php
// src/views/players/profile.php

// Include necessary files
include 'src/views/templates/header.php';

// Assume $player is an associative array of player data passed from the controller
?>

<div class="container">
    <h2>Player Profile: <?php echo htmlspecialchars($player['name']); ?></h2>

    <div class="player-details">
        <p><strong>ID:</strong> <?php echo htmlspecialchars($player['id']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($player['name']); ?></p>
        <p><strong>Position:</strong> <?php echo htmlspecialchars($player['position']); ?></p>
        <p><strong>Team:</strong> <?php echo htmlspecialchars($player['team']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($player['age']); ?></p>
        <p><strong>Stats:</strong> <?php echo htmlspecialchars($player['stats']); ?></p> <!-- Example for additional statistics -->
    </div>

    <div class="player-actions">
        <a href="edit.php?id=<?php echo htmlspecialchars($player['id']); ?>" class="btn btn-primary">Edit Profile</a>
        <a href="delete.php?id=<?php echo htmlspecialchars($player['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this player?');">Delete Player</a>
        <a href="list.php" class="btn btn-secondary">Back to Player List</a>
    </div>
</div>

<?php
// Include the footer
include 'src/views/templates/footer.php';
?>
