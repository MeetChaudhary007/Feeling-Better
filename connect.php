<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feeling_better";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get the database connection
function getDBConnection() {
    global $conn;
    return $conn;
}

?>
