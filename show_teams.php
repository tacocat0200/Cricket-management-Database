<?php

// Connect to MySQL database
$servername = "localhost";
$username = "shripurna";
$password = "rRyt@w2";
$dbname = "cricket_management_6";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query database for teams table
$sql = "SELECT * FROM Teams";
$result = $conn->query($sql);

// Display table contents
if ($result->num_rows > 0) {
    echo "<table><tr><th>Team ID</th><th>Team Name</th><th>Coach Name</th><th>Home Ground</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["team_id"]."</td><td>".$row["team_name"]."</td><td>".$row["coach_name"]."</td><td>".$row["home_ground"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();

?>
