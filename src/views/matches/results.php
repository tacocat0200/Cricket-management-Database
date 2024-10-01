<?php
// src/views/matches/results.php

// Include necessary files
include 'src/views/templates/header.php';

// Assume $results is an array of match results passed from the controller
?>

<div class="container">
    <h2>Match Results</h2>

    <div class="match-search">
        <form method="GET" action="results.php">
            <input type="text" name="search" placeholder="Search match results...">
            <button type="submit">Search</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Home Team</th>
                <th>Away Team</th>
                <th>Score</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($results)): ?>
                <tr>
                    <td colspan="6">No results found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($results as $match): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($match['id']); ?></td>
                        <td><?php echo htmlspecialchars($match['home_team']); ?></td>
                        <td><?php echo htmlspecialchars($match['away_team']); ?></td>
                        <td><?php echo htmlspecialchars($match['score']); ?></td>
                        <td><?php echo htmlspecialchars($match['date']); ?></td>
                        <td>
                            <a href="match_details.php?id=<?php echo htmlspecialchars($match['id']); ?>">View Details</a> |
                            <a href="edit_result.php?id=<?php echo htmlspecialchars($match['id']); ?>">Edit</a> |
                            <a href="delete_result.php?id=<?php echo htmlspecialchars($match['id']); ?>" onclick="return confirm('Are you sure you want to delete this result?');">Delete</a>
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
