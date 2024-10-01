<?php
// src/views/matches/schedule.php

// Include necessary files
include 'src/views/templates/header.php';

// Assume $matches is an array of scheduled matches passed from the controller
?>

<div class="container">
    <h2>Match Schedule</h2>

    <div class="match-search">
        <form method="GET" action="schedule.php">
            <input type="text" name="search" placeholder="Search matches...">
            <button type="submit">Search</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Home Team</th>
                <th>Away Team</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($matches)): ?>
                <tr>
                    <td colspan="6">No matches scheduled.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($matches as $match): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($match['id']); ?></td>
                        <td><?php echo htmlspecialchars($match['home_team']); ?></td>
                        <td><?php echo htmlspecialchars($match['away_team']); ?></td>
                        <td><?php echo htmlspecialchars($match['date']); ?></td>
                        <td><?php echo htmlspecialchars($match['time']); ?></td>
                        <td>
                            <a href="match_details.php?id=<?php echo htmlspecialchars($match['id']); ?>">View Details</a> |
                            <a href="edit_match.php?id=<?php echo htmlspecialchars($match['id']); ?>">Edit</a> |
                            <a href="delete_match.php?id=<?php echo htmlspecialchars($match['id']); ?>" onclick="return confirm('Are you sure you want to delete this match?');">Delete</a>
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
