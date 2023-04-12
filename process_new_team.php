<?php

// Get the form data
$team_id = $_POST['team_id'];
$team_name = $_POST['team_name'];
$coach_name = $_POST['coach_name'];
$home_ground = $_POST['home_ground'];

// Connect to the database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Insert the new row
$sql = "INSERT INTO Teams (team_id, team_name, coach_name, home_ground)
        VALUES ('$team_id', '$team_name', '$coach_name', '$home_ground')";

if (mysqli_query($conn, $sql)) {
  echo "New team entry created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
