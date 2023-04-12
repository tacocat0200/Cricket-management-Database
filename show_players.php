<?php
// Database connection
$servername = "localhost";
$username = "shripurna";
$password = "rRyt@w2";
$dbname = "cricket_management_6";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to select all players
$sql = "SELECT * FROM Players";
$result = $conn->query($sql);

// Check if there are players
if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    echo $row["player_id"] . " | " . $row["player_name"] . " | " . $row["age"] . " | " . $row["position"] . " | " . $row["team_id"] . "<br>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>
