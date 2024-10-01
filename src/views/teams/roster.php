<?php
// src/views/teams/roster.php

// Include necessary files
include 'src/views/templates/header.php';

// Assume $team is an associative array with team details passed from the controller
// Assume $players is an array of players associated with the team
?>

<div class="container">
    <h2>Roster for Team: <?php echo htmlspecialchars($team['name']); ?></h2>

    <div class="player-actions">
        <a href="add_player.php?team_id=<?php echo htmlspecialchars($team['id']); ?>" class="btn btn-primary">Add Player</a>
        <a href="teams.php" class="btn btn-secondary">Back to Team List</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Age</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($players)): ?>
                <tr>
                    <td colspan="5">No players found in this roster.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($players as $player): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($player['id']); ?></td>
                        <td><?php echo htmlspecialchars($player['name']); ?></td>
                        <td><?php echo htmlspecialchars($player['position']); ?></td>
                        <td><?php echo htmlspecialchars($player['age']); ?></td>
                        <td>
                            <a href="player_profile.php?id=<?php echo htmlspecialchars($player['id']); ?>">View</a> |
                            <a href="edit_player.php?id=<?php echo htmlspecialchars($player['id']); ?>">Edit</a> |
                            <a href="remove_player.php?id=<?php echo htmlspecialchars($player['id']); ?>&team_id=<?php echo htmlspecialchars($team['id']); ?>" onclick="return confirm('Are you sure you want to remove this player from the team?');">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// Include the footer
include 'src/views/templates/footer.php';
?>
