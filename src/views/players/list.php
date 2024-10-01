<?php
// src/views/players/list.php

// Include necessary files
include 'src/views/templates/header.php';

// Assume $players is an array of player data passed from the controller
?>

<div class="container">
    <h2>Player List</h2>

    <div class="player-search">
        <form method="GET" action="players.php">
            <input type="text" name="search" placeholder="Search players...">
            <button type="submit">Search</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Team</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($players)): ?>
                <tr>
                    <td colspan="5">No players found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($players as $player): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($player['id']); ?></td>
                        <td><?php echo htmlspecialchars($player['name']); ?></td>
                        <td><?php echo htmlspecialchars($player['position']); ?></td>
                        <td><?php echo htmlspecialchars($player['team']); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo htmlspecialchars($player['id']); ?>">View</a> |
                            <a href="edit.php?id=<?php echo htmlspecialchars($player['id']); ?>">Edit</a> |
                            <a href="delete.php?id=<?php echo htmlspecialchars($player['id']); ?>" onclick="return confirm('Are you sure you want to delete this player?');">Delete</a>
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
