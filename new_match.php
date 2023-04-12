<?php
// Get the match details from the form
$match_id = $_POST['match_id'];
$match_date = $_POST['match_date'];
$venue = $_POST['venue'];
$team_1_id = $_POST['team_1_id'];
$team_2_id = $_POST['team_2_id'];
$winner_id = $_POST['winner_id'];

// Connect to the database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert the match details into the database
$sql = "INSERT INTO Matches (match_id, match_date, venue, team_1_id, team_2_id, winner_id)
        VALUES ('$match_id', '$match_date', '$venue', '$team_1_id', '$team_2_id', '$winner_id')";

if ($conn->query($sql) === TRUE) {
    echo "New match created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
