<?php
// src/views/teams/list.php

// Include necessary files
include 'src/views/templates/header.php';

// Assume $teams is an array of team data passed from the controller
?>

<div class="container">
    <h2>Team List</h2>

    <div class="team-search">
        <form method="GET" action="teams.php">
            <input type="text" name="search" placeholder="Search teams...">
            <button type="submit">Search</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Coach</th>
                <th>Home Ground</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($teams)): ?>
                <tr>
                    <td colspan="5">No teams found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($teams as $team): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($team['id']); ?></td>
                        <td><?php echo htmlspecialchars($team['name']); ?></td>
                        <td><?php echo htmlspecialchars($team['coach']); ?></td>
                        <td><?php echo htmlspecialchars($team['home_ground']); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo htmlspecialchars($team['id']); ?>">View</a> |
                            <a href="edit.php?id=<?php echo htmlspecialchars($team['id']); ?>">Edit</a> |
                            <a href="delete.php?id=<?php echo htmlspecialchars($team['id']); ?>" onclick="return confirm('Are you sure you want to delete this team?');">Delete</a>
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
