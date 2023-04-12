<?php
// establish connection to the database
$conn = mysqli_connect("localhost", "username", "password", "cricket_management_6");

// check if connection was successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// retrieve data from the form
$player_id = $_POST['player_id'];
$player_name = $_POST['player_name'];
$age = $_POST['age'];
$position = $_POST['position'];
$team_id = $_POST['team_id'];

// create the SQL query to insert a new player
$sql = "INSERT INTO Players (player_id, player_name, age, position, team_id) 
		VALUES ('$player_id', '$player_name', $age, '$position', '$team_id')";

// execute the query and check if it was successful
if (mysqli_query($conn, $sql)) {
	echo "New player added successfully!";
} else {
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// close the database connection
mysqli_close($conn);
?>


<?php
	// Database connection
	$conn = mysqli_connect("localhost", "username", "password", "cricket_management_6");

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// Get form data
	$team_id = $_POST["team_id"];
	$team_name = $_POST["team_name"];
	$coach_name = $_POST["coach_name"];
	$home_ground = $_POST["home_ground"];

	// Insert new team data into Teams table
	$sql = "INSERT INTO Teams (team_id, team_name, coach_name, home_ground)
			VALUES ('$team_id', '$team_name', '$coach_name', '$home_ground')";

	if (mysqli_query($conn, $sql)) {
		echo "New team added successfully.";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

    // Get form data
	$team_id = $_POST["team_id"];
	$team_name = $_POST["team_name"];
	$coach_name = $_POST["coach_name"];
	$home_ground = $_POST["home_ground"];

	// Insert new team data into Teams table
	$sql = "INSERT INTO Teams (team_id, team_name, coach_name, home_ground)
			VALUES ('$team_id', '$team_name', '$coach_name', '$home_ground')";

	if (mysqli_query($conn, $sql)) {
		echo "New team added successfully.";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	// Close database connection
	mysqli_close($conn);
?>
